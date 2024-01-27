<?php

namespace Ag84ark\SmartBill\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    private string $vatCode = '12345678';
    private string $invoiceSeries = 'INV';

    private string $receiptSeries = 'REC';

    private string $proformaSeries = 'PRO';

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('smartbill.vatCode', $this->vatCode);
        config()->set('smartbill.invoiceSeries', $this->invoiceSeries);
        config()->set('smartbill.receiptSeries', $this->receiptSeries);
        config()->set('smartbill.proformaSeries', $this->proformaSeries);
    }

    public function getVatCode(): string
    {
        return $this->vatCode;
    }

    public function getInvoiceSeries(): string
    {
        return $this->invoiceSeries;
    }

    public function getReceiptSeries(): string
    {
        return $this->receiptSeries;
    }

    public function getProformaSeries(): string
    {
        return $this->proformaSeries;
    }
}
