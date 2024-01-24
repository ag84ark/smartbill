<?php

namespace Ag84ark\SmartBill\Exceptions;

class InvalidPaymentTypeException extends \Exception
{
    public function __construct(string $paymentType)
    {
        parent::__construct("Invalid payment type: {$paymentType}");
    }
}
