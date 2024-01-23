<?php

namespace Ag84ark\SmartBill\Exceptions;

class InvalidDateException extends \Exception
{
    public function __construct(string $date)
    {
        parent::__construct("Invalid date: {$date}");
    }
}