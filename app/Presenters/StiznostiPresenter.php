<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Models\ComplaintFacade;
use App\Models\UserFacade;
use DateTime;

final class StiznostiPresenter extends Nette\Application\UI\Presenter
{
    public function startup()
    {
        parent::startup();
        if ($this->getUser()->isLoggedIn() === false && $this->getAction() == 'default') {
            $this->flashMessage('Tato sekce není přístupná bez přihlášení', 'danger');
            $this->redirect('Admin:login');
        }
    }

    public function __construct(private ComplaintFacade $complaintFacade, private UserFacade $userFacade)
    {
    }
    
    public function actionDefault()
    {
        $this->template->style = "stiznosti";
    }

    protected function createComponentCareerForm(): Form
    {
        $form = new Form();
        $form->addEmail('email','Email')
            ->addRule(Form::EMAIL, 'Zadejte platný e-mail')
            ->setRequired('Zadejte svůj email');
        $form->addText('theme')
            ->setRequired('Zadejte prosím podmět podání stížnosti');
        $form->addTextArea('text')
            ->setRequired('Zadejte prosím s čím jste byli nespokojeni.');
        $form->addSubmit('send', 'Odeslat stížnost');
        $form->onSuccess[] = [$this, 'complaintFormSuccess'];
        return $form;
    }
    
    public function complaintFormSuccess(Form $form)
    {
        $values = $form->getValues();

        date_default_timezone_set('Europe/Prague');
        $date = date('Y-m-d H:i:s');

        $complaintData = [
            'date_arrive' => $date,
            'email' => $values->email,
            'theme' => $values->theme,
            'mainText' => $values->text
        ];

        try {
			$this->complaintFacade->addComplaint($complaintData);
            $this->redirect('Home:');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateEmailException;
		}
    }
    
}
