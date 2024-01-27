<?php

namespace Ag84ark\SmartBill\Exceptions;

class InvalidDocumentTypeException extends \Exception
{
    public function __construct(string $documentType)
    {
        parent::__construct("Invalid document type: {$documentType}");
    }
}
