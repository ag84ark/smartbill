<?php

namespace Ag84ark\SmartBill\Enums;

class PaymentTypeEnum
{
    public const OrdinPlata = 'Ordin plata';

    public const Chitanta = 'Chitanta';

    public const Card = 'Card';

    public const CEC = 'CEC';

    public const BiletOrdin = 'Bilet ordin';

    public const MandatPostal = 'Mandat postal';

    public const Other = 'Alta incasare';

    public const Alta = 'Alta incasare';

    public const BonFiscal = 'Bon';

    public static function isValid(string $type): bool
    {
        return in_array($type, [
            self::OrdinPlata,
            self::Chitanta,
            self::Card,
            self::CEC,
            self::BiletOrdin,
            self::MandatPostal,
            self::Other,
            self::Alta,
            self::BonFiscal,
        ]);
    }
}
