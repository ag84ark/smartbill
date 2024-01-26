# SmartBill.ro Service Provider

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ag84ark/smartbill.svg?style=flat-square)](https://packagist.org/packages/ag84ark/smartbill)
[![Total Downloads](https://img.shields.io/packagist/dt/ag84ark/smartbill.svg?style=flat-square)](https://packagist.org/packages/ag84ark/smartbill)

SmartBill.ro API integration for Laravel.  
This started as a fork of from  [necenzurat/smartbill](https://github.com/necenzurat/smartbill), and now it's a standalone package.

## Installation

You can install the package via composer:

```bash
composer require ag84ark/smartbill
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Ag84ark\SmartBill\SmartBillServiceProvider" --tag="laravel-smartbill-config"
```

## Usage

### Using resources

```php
use Ag84ark\SmartBill\SmartBill;
use Ag84ark\SmartBill\Resources\Invoice;
use Ag84ark\SmartBill\Resources\Client;
use Ag84ark\SmartBill\Resources\InvoiceProduct;

$invoice = Invoice::make();

$invoice->setClient( Client::make()
    ->setName("ACME CO")
    ->setVatCode("RO12345678")
    ->setRegCom("")
    ->setAddress("Main street, no 10")
    ->setIsTaxPayer(false)
    ->setCity("Bucharest")
    ->setCountry("Romania")
    ->setEmail("acme@example.com")
);

$invoice->addProduct(InvoiceProduct::make()
    ->setName("Produs 1")
    ->setCode("ccd1")
    ->setMeasuringUnitName("buc")
    ->setCurrency("RON")
    ->setQuantity(2)
    ->setPrice(10)
    ->setIsTaxIncluded(true)
    ->setTaxName("Redusa")
    ->setTaxPercentage(9)
    ->setIsService(false)
    ->setSaveToDb(false)
);

$invoice->addProduct(InvoiceProduct::makeDiscountItem()
    ->setName("Discount")
    ->setMeasuringUnitName("buc")
    ->setCurrency("RON")
    ->setQuantity(1)
    ->setPrice(10)
    ->setIsService(false)
    ->setSaveToDb(false)
);

echo 'Emitere factura simpla: ';
try {
    $smartbill = new SmartBill();
    $output = $smartbill->createInvoice($invoice); //see docs for response
    $invoiceNumber = $output['number'];
    $invoiceSeries = $output['series'];
    echo $invoiceSeries . $invoiceNumber;
} catch (\Exception $ex) {
    echo $ex->getMessage();
}

```


### Using array data

```php

$invoice = [
    'companyVatCode' => config('smartbill.vatCode'),
    'client' 		=> [
        'name' 			=> "ACME CO",
        'vatCode' 		=> "RO12345678",
        'regCom' 		=> "",
        'address' 		=> "Main street, no 10",
        'isTaxPayer' 	=> false,
        'city' 			=> "Bucharest",
        'country' 		=> "Romania",
        'email' 		=> "acme@example.com",
    ],
    'issueDate'      => date('Y-m-d'),
    'seriesName'     => config('smartbill.invoiceSeries'),
    'isDraft'        => false,
    'dueDate'		=> date('Y-m-d', time() + 3600 * 24 * 30),
    'mentions'		=> '',
    'observations'   => '',
    'deliveryDate'   => date('Y-m-d', time() + 3600 * 24 * 10),
    'precision'      => 2,
    'products'		=> [
        [
            'name' 				=> "Produs 1",
            'code' 				=> "ccd1",
            'isDiscount' 		=> false,
            'measuringUnitName' => "buc",
            'currency' 			=> "RON",
            'quantity' 			=> 2,
            'price' 			=> 10,
            'isTaxIncluded' 	=> true,
            'taxName' 			=> "Redusa",
            'taxPercentage' 	=> 9,
            'isService'         => false,
            'saveToDb'          => false,
        ],
    ],
];

echo 'Emitere factura simpla: ';
try {
    $smartbill = new SmartBill();
    $output = $smartbill->createInvoiceFromArray($invoice); //see docs for response
    $invoiceNumber = $output['number'];
    $invoiceSeries = $output['series'];
    echo $invoiceSeries . $invoiceNumber;
} catch (\Exception $ex) {
    echo $ex->getMessage();
}
```



### Testing

``` bash
In the making
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, use the issue tracker.

## License

The WTFPL. Please see [License File](LICENSE.md) for more information.
