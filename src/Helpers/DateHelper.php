<?php

namespace Ag84ark\SmartBill\Helpers;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;

class DateHelper
{
    public static function isValidDate(string $date): bool
    {
        return (bool)strtotime($date);
    }

    /**
     * @throws InvalidDateException
     */
    public static function getYMD(string $date): string
    {
        if (!self::isValidDate($date)) {
            throw new InvalidDateException($date);
        }
        return date('Y-m-d', strtotime($date));
    }
}