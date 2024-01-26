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

    'username' => env('SMARTBILL_USERNAME', ''),
    'token' => env('SMARTBILL_TOKEN', ''),

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
    
    'vatCode' => env('SMARTBILL_VAT_CODE', ''),

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

    'invoiceSeries' => env('SMARTBILL_INVOICE_SERIES', 'TEST-INV'),
    'proformaSeries' => env('SMARTBILL_PROFORMA_SERIES', 'TEST-PRO'),
    'receiptSeries' => env('SMARTBILL_RECEIPT_SERIES', 'TEST-REC'),

    // Default tax name as in SmartBill
    'defaultTaxName' => env('SMARTBILL_DEFAULT_TAX_NAME', 'Normala'),

    // Default VAT (TVA) percentage
    'defaultTaxPercentage' => env('SMARTBILL_DEFAULT_TAX_PERCENTAGE', 19),

    // Default due days for invoice creation
    'defaultDueDays' => env('SMARTBILL_DEFAULT_DUE_DAYS', 30),

    // Does the company pay VAT (TVA) or not
    'companyPaysVAT' => env('SMARTBILL_COMPANY_PAYS_VAT', true),

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
