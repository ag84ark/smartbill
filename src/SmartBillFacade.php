<?php

namespace Ag84ark\SmartBill;

use Illuminate\Support\Facades\Facade;

class SmartBillFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smartbill';
    }
}
