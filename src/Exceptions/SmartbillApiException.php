<?php

namespace Ag84ark\SmartBill\Exceptions;

class SmartbillApiException extends \Exception
{
    private int $statusCode;

    public function __construct($message = "", $statusCode = 0, \Throwable $previous = null)
    {
        $message = $message ?: 'Smartbill API Exception';
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
