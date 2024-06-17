<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\Attributes\Persistent;
use App\Forms\OrderFormFactory;
use App\Models\UserFacade;
use App\Models\ServiceFacade;
use App\Models\OrderFacade;
use App\Models\VisitsFacade;
use App\Models\Order;
use Endroid\QrCode\QrCode;
use Rikudou\CzQrPayment\QrPayment;
use Rikudou\CzQrPayment\Options\QrPaymentOptions;
use DateTimeImmutable;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private OrderFormFactory $orderFormFactory,
        private UserFacade $userFacade,
        private ServiceFacade $serviceFacade,
        private OrderFacade $orderFacade,
        private Order $order,
        private VisitsFacade $visitsFacade
    )
    {   
    }

    public function startup()
    {
        parent::startup();
        if ($this->getUser()->isLoggedIn() === false && ($this->getAction() == 'order' ||  $this->getAction() == 'payment')) {
            $this->flashMessage('Tato sekce není přístupná bez přihlášení', 'danger');
            $this->redirect('Admin:login');
        }
    }

    public function actionDefault()
    {
        $this->template->style = "hlavni";
        $this->template->services = $this->serviceFacade->getServices();
        $ipAddress = $this->getHttpRequest()->getRemoteAddress();
        $this->visitsFacade->addVisitor($ipAddress);
    }

    public function actionDetail(int $id): void
    {
        $this->template->style = "detail";
        $services = $this->serviceFacade->getServicesById($id);
        if (!$services) {
            throw new \Nette\Application\BadRequestException('Služba nenalezena', 404);
        }
        $this->template->services = $services;
    }

    public function actionOrder(int $service_id, int $program_id): void
    {
        $this->template->style = "order";

        $service = $this->serviceFacade->getServiceNameAndPriceById($service_id, $program_id);
        if(!$service) {
            throw new \Nette\Application\BadRequestException('Nesprávné parametry', 404);
        }
        $this->order->setServiceName($service['serviceName']);
        $this->order->setAmount($service['amount']);
        $this->order->setOrderNumber(time());
        $this->order->setProgram($service['program']);
        $this->order->setCustomerId($this->getUser()->getId());
        $this->order->setServiceId($service_id);
        $this->template->order = $this->order;
        $this->template->loggedUser = $this->userFacade->getUserById($this->order->getCustomerId());
    }

    public function actionPayment($id)
    {
        $amount = $this->orderFacade->getAmountById($id);
        $this->template->style = "payment";
        $this->template->orderNumber = $id;
        $dueDate = new DateTimeImmutable('+14 days');
        $this->template->amount = $amount;
        $this->template->dueDate = $dueDate;
        $payment = QrPayment::fromAccountAndBankCode('670100-2218099081', '6210');
        $payment->setOptions([
        QrPaymentOptions::VARIABLE_SYMBOL => $id,
        QrPaymentOptions::AMOUNT => $amount,
        QrPaymentOptions::CURRENCY => "CZK",
        QrPaymentOptions::INSTANT_PAYMENT => true,
        ]);
        $qrCode = $payment->getQrCode();
        $this->template->qrCode = base64_encode($qrCode->getRawString());
    }

    protected function createComponentOrderForm(): Form
    {
        $form = $this->orderFormFactory->render();
        $form->onSuccess[] = [$this, 'orderFormSuccess'];
        return $form;
    }

    public function orderFormSuccess(Form $form)
    {
        $values = $form->getValues();
        $address = $values->street;
        $city = $values->city;
        $orderData = [
            'order_id' => $this->order->getOrderNumber(),
            'user_id' => $this->order->getCustomerId(),
            'service_id' => $this->order->getServiceId(),
            'price' => (int)$this->order->getAmount(),
            'type_service' => $this->order->getProgram(),
            'address' => $address,
            'city' => $city,
            'orderDate' => DateTimeImmutable::createFromFormat('Y-m-d', $values->dateInput),
            'orderTime' => $values->timeInput,
            'arriveDate' => (new DateTimeImmutable())->format('Y-m-d H:i'),
            'note' => $values->infoText
        ];
        
        try {
            $this->userFacade->updateUserAddress($orderData['user_id'], $orderData['address'], $orderData['city']);
            $this->orderFacade->addOrder($orderData);
        } catch (\Exception $e) {
            $this->flashMessage('Chyba při objednávce', 'danger');
            $this->redirect('this');
        }
        $this->redirect('Home:payment', $this->order->getOrderNumber());
    }
}