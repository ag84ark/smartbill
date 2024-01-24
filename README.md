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
php artisan vendor:publish --provider="Ag84ark\Smartbill\SmartbillServiceProvider" --tag="laravel-smartbill-config"
```

## Usage

<details><summary>Click for usage</summary>
<p>

```php

$invoice = [
    'companyVatCode' => config('smartbill.vatCode'),
    'client' 		=> [
        'name' 			=> "Intelligent IT",
        'vatCode' 		=> "RO12345678",
        'regCom' 		=> "",
        'address' 		=> "str. Sperantei, nr. 5",
        'isTaxPayer' 	=> false,
        'city' 			=> "Sibiu",
        'country' 		=> "Romania",
        'email' 		=> "office@intelligent.ro",
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

</p>
</details>


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
