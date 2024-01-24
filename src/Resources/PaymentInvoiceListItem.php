<?php

namespace Ag84ark\SmartBill\Resources;

class PaymentInvoiceListItem
{
    private string $seriesName;
    private string $number;

    public function __construct($number)
    {
        $this->seriesName = config('smartbill.invoiceSeries');
        $this->number = $number;
    }

    public function toArray()
    {
        return [
            'seriesName' => $this->seriesName,
            'number' => $this->number,
        ];
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): PaymentInvoiceListItem
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): PaymentInvoiceListItem
    {
        $this->number = $number;

        return $this;
    }
}
