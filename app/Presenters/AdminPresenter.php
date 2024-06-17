<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Models\UserFacade;
use App\Models\CareerFacade;
use App\Models\ComplaintFacade;
use App\Models\OrderFacade;
use App\Models\VisitsFacade;
use App\Forms\LoginFormFactory;
use App\Forms\RegisterFormFactory;
use App\Forms\UserFormFactory;

final class AdminPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private UserFormFactory $userFormFactory,
        private LoginFormFactory $loginFormFactory,
        private RegisterFormFactory $registerFormFactory,
        private UserFacade $userFacade,
        private ComplaintFacade $complaintFacade,
        private CareerFacade $careerFacade,
        private OrderFacade $orderFacade,
        private VisitsFacade $visitsFacade
    )
    {
    }

    public function startup()
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn() && $this->getAction() !== 'login' && $this->getAction() !== 'register') {
            $this->flashMessage('Tato sekce není přístupná bez přihlášení','danger');
            $this->redirect('login');
        }

        if ($this->getUser()->isLoggedIn() && ($this->getAction() === 'login' || $this->getAction() === 'register') ) {
            $roles = $this->getUser()->getIdentity()->getRoles();
            if (in_array(1, $roles)) {
                $this->redirect('dashboard');
            } elseif (in_array(2, $roles)) {
                $this->redirect('profile');
            }
        }

        if ($this->getUser()->isLoggedIn() && ($this->getAction() === 'dashboard' || $this->getAction() === 'profile')) {
            $roles = $this->getUser()->getIdentity()->getRoles();
            if (in_array(1, $roles) && $this->getAction() !== 'dashboard') {
                $this->flashMessage('Tato sekce je přístupná pouze běžným uživatelům', 'danger');
                $this->redirect('Home:');
            } elseif (in_array(2, $roles) && $this->getAction() !== 'profile') {
                $this->flashMessage('Tato sekce je přístupná pouze administrátorům', 'danger');
                $this->redirect('Home:');
            }
        }
    }

    public function actionLogOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení proběhlo úspěšně','success');
        $this->redirect('login');
    }

    public function actionLogIn()
    {
        $this->setLayout('admin.login');
        $this->template->style = "login";
    }

    protected function createComponentLoginForm(): Form
    {
        return $this->loginFormFactory->render();
    }

    public function actionRegister()
    {
        $this->setLayout('admin.login');
        $this->template->style = "login";
    }

    protected function createComponentRegisterForm(): Form
    {
        return $this->registerFormFactory->render();
    }
    
    public function actionProfile()
    {
        $this->setLayout('admin.profile');
        $this->template->style = "profile";
        $userId = $this->user->getIdentity()->getId();
        $this->template->selectedProfile=$this->userFacade->getUserById($userId);
        $this->template->selectedOrder = $this->orderFacade->getOrderById($userId);
        $this->template->countOrders = $this->orderFacade->getUserOrderCount($userId);
    }

    protected function createComponentProfileForm()
    {
        $form = $this->userFormFactory->render();
        $form['gender']->setDefaultValue($this->userFacade->getUserGenderById($this->user->getIdentity()->getId()) ? 'M' : 'F');
        $form->onSuccess[] = [$this->userFormFactory, 'profileFormSuccess'];
        return $form;
    }

    protected function createComponentPasswordForm()
    {
        return $this->userFormFactory->renderPasswordForm();
    }

    public function actionDashboard()
    {
        $this->setLayout('admin.dashboard');
        $this->template->style = "admin";
        $this->template->allUsers = $this->userFacade->getAllUsers();
        $this->template->countUsers = $this->userFacade->getNumberOfUsers();
        $this->template->countCareer = $this->careerFacade->getNumberOfCareer();
        $this->template->countComplaint = $this->complaintFacade->countComplaints();
        $this->template->newOrdersCount = $this->orderFacade->countRecordsWhereOrderStatusEquals(0);
        $this->template->countMan = $this->userFacade->countGender('M');
        $this->template->countWoman = $this->userFacade->countGender('F');
        $this->template->service1 = $this->orderFacade->countServiceById(1);
        $this->template->service2 = $this->orderFacade->countServiceById(2);
        $this->template->service3 = $this->orderFacade->countServiceById(3);
        $this->template->service4 = $this->orderFacade->countServiceById(4);
        $this->template->service5 = $this->orderFacade->countServiceById(5);
        $this->template->service6 = $this->orderFacade->countServiceById(6);
        $this->template->visitorsNumber = $this->visitsFacade->getNumberOfVisitors();
        
        date_default_timezone_set('Europe/Prague');
        $dat = date('d.n.');
        $days = date('l');
        $days_translation = array(
            'Monday' => 'Pondělí',
            'Tuesday' => 'Úterý',
            'Wednesday' => 'Středa',
            'Thursday' => 'Čtvrtek',
            'Friday' => 'Pátek',
            'Saturday' => 'Sobota',
            'Sunday' => 'Neděle'
        );
        
        $translated_day = isset($days_translation[$days]) ? $days_translation[$days] : $days;
        $this->template->date = $translated_day . ' ' . $dat;
    }
    
    private $selectedUserId;
    public function actionProfileInfo(int $id):void
    {
        $selectedUser = $this->userFacade->getUserById($id);
        $countOrders = $this->orderFacade->getUserOrderCount($id);

        if (!$selectedUser) {
            $this->redirect('dashboard');
        }
        
        $this->template->style = "profileInfo";
        $this->setLayout('admin.profileInfo');
        $this->selectedUserId = $id;
        $this->template->selectedUser = $selectedUser;
        $this->template->countOrders = $countOrders;
        $this->template->selectedUserId = $id;
    }

    protected function createComponentProfileInfoForm()
    {   $id = $this->selectedUserId;
        $form = $this->userFormFactory->render();
        $form['gender']->setDefaultValue($this->userFacade->getUserGenderById($id) ? 'M' : 'F');
        $form->addHidden('id',$id);
        $form->addSubmit('save', 'Přepsat data')->onClick[] = [$this->userFormFactory, 'saveButtonPressed'];
        $form->addSubmit('delete', 'Smazat uživatele')->onClick[] = [$this->userFormFactory, 'deleteButtonPressed'];
        return $form;
    }

    public function actionComplaint()
    {
        $this->setLayout('admin.subpage');
        $this->template->style = "complaint";
        $this->template->object = $this->complaintFacade;
        $this->template->complaints = $this->complaintFacade->getAllComplaints();
        $this->template->countComplaints = $this->complaintFacade->countComplaints();
    }

    public function handleRemoveComplaint(int $id)
    { 
        try {
            $this->complaintFacade->removeComplaint($id);
        } catch (\Exception $e) {
            $this->flashMessage('Chyba při vyřízení stížnosti', 'danger');
            $this->redirect('dashboard'); 
        }
        $this->redirect('this');
    }

    public function actionCareer()
    {
        $this->setLayout('admin.subpage');
        $this->template->style = "careerA";
        $this->template->careers = $this->careerFacade->getAllCareer();
        $this->template->countCareer = $this->careerFacade->getNumberOfCareer();
        $this->template->countAllCareer = $this->careerFacade->getNumberOfAllCareer();
        $this->template->countManCareer = $this->careerFacade->countGender('M');
        $this->template->countWomanCareer = $this->careerFacade->countGender('F');
        $this->template->averageAge = $this->careerFacade->averageAge();
    }

    public function handleUpdateApplication(int $id)
    { 
        try {
            $this->careerFacade->updateApplication($id);
        } catch (\Exception $e) {
            $this->flashMessage('Chyba při vyřízení žádosti o práci', 'danger');
            $this->redirect('dashboard'); 
        }
        $this->redirect('this');
    }

    public function actionPay()
    {
        $this->setLayout('admin.subpage');
        $this->template->style = "pay";
        $this->template->orders = $this->orderFacade->getOrders();
        $this->template->newOrdersCount = $this->orderFacade->countRecordsWhereOrderStatusEquals(0);
        $this->template->countDoneOrders = $this->orderFacade->countRecordsWhereOrderStatusEquals([1, 2]);
        $this->template->countAcceptedOrders = $this->orderFacade->countRecordsWhereOrderStatusEquals(1);
        $this->template->countRejectedOrders = $this->orderFacade->countRecordsWhereOrderStatusEquals(2);
        $this->template->averagePayment = $this->orderFacade->averagePayment();
        $this->template->sales = $this->orderFacade->sales();
    }

    public function handleUpdateStatus(string $order_id, int $value)
    { 
        try {
            $this->orderFacade->updateStatus($order_id, $value); 
        } catch (\Exception $e) {
            $this->flashMessage('Chyba při změně stavu objednávky', 'danger');
            $this->redirect('dashboard'); 
        }
        $this->redirect('this');
    }
}