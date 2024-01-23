<?php

namespace Ag84ark\SmartBill\Exceptions;

class InvalidTaxNameException extends \Exception
{
    public function __construct(string $taxName)
    {
        parent::__construct("Invalid tax name: {$taxName}");
    }
}