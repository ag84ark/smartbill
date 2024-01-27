<?php

namespace Ag84ark\SmartBill\Enums;

class DocumentTypeEnum
{
    public const Invoice = 'factura'; // en

    public const Factura = 'factura';

    public const Proforma = 'proforma';

    public const Receipt = 'chitanta'; // en

    public const Chitanta = 'chitanta';

    public static function isValid(string $type): bool
    {
        return in_array($type, [self::Invoice,  self::Proforma, self::Receipt]);
    }

    /**
     * Checks if the given type is valid for email.
     *
     * @param string $type The type to check if it is valid for email.
     *
     * @return bool Returns true if the given type is valid for email, and false otherwise.
     */
    public static function isValidForEmail(string $type): bool
    {
        return in_array($type, [self::Invoice, self::Proforma]);
    }
}
