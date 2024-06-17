<?php

namespace App\Models;

class Order
{
    private $orderNumber;
    private $customer_id;
    private $service_id;
    private $amount;
    private $program;
    private $serviceName;

    public function __construct(int $orderNumber, int $customer_id, int $service_id, int $amount, string $serviceName, string $program)
    {
        $this->orderNumber = $orderNumber;
        $this->customer_id = $customer_id;
        $this->service_id = $service_id;
        $this->amount = $amount;
        $this->serviceName = $serviceName;
        $this->program = $program;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amout)
    {
        $this->amount = $amout;
    }

    public function getServiceId()
    {
        return $this->service_id;
    }

    public function setServiceId($serviceId)
    {
        $this->service_id = $serviceId;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }
    
    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program)
    {
        $this->program = $program;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;
    }
}