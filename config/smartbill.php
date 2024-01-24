<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SmartBill credentials
    |--------------------------------------------------------------------------
    |
    | Here you need to supply the credentials for the SmartBill platform
    | and the token you can be found at the following link:
    | https://cloud.smartbill.ro/core/integrari/
    | and the username is your login email.
    |
    */

    'username' => '',
    'token' => '',

    /*
    |--------------------------------------------------------------------------
    | Company VAT code
    |--------------------------------------------------------------------------
    |
    | The VAT code for the SmartBill account, you can have multiple
    | companies in SmartBill, but at the moment currently
    | one account is supported in this package
    | As declared in SmartBill in https://cloud.smartbill.ro/core/configurare/date-firma/ under CIF
    | EX: RO12345678
    */
    
    'vatCode' => '',

    /*
    |--------------------------------------------------------------------------
    | Invoice Series
    |--------------------------------------------------------------------------
    |
    | Here you may define your invoice, proforma and receipt starting series
    | But first you need to define them in SmartBill at the following link
    | https://cloud.smartbill.ro/core/configurare/serii/
    |
    */

    'invoiceSeries' => 'TEST-INV',
    'proformaSeries' => 'TEST-PRO',
    'receiptSeries' => 'TEST-REC',

    // Default tax name as in SmartBill
    'defaultTaxName' => 'Normala',

    // Default VAT (TVA) percentage
    'defaultTaxPercentage' => 19,

    // Default due days for invoice creation
    'defaultDueDays' => 30,

    // Does the company pay VAT (TVA) or not
    'companyPaysVAT' => true,

    // Romanian tax types declared in SmartBill
    'taxNames' => [
        'Normala', // 19%
        'Redusa', // 9%
        'SFDD', // 0%
        'SDD', // 0%
        'TVA Inclus', // 0%
        'Taxare inversa', // 0%
        'Redusa locuinte', // 5%
        'Veche', // 24%
    ],

];
