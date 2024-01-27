<?php

namespace Ag84ark\SmartBill;

use Ag84ark\SmartBill\Endpoints\EmailEndpoint;
use Ag84ark\SmartBill\Endpoints\InvoiceEndpoint;
use Ag84ark\SmartBill\Endpoints\PaymentEndpoint;
use Ag84ark\SmartBill\Endpoints\ProformaEndpoint;
use Ag84ark\SmartBill\Endpoints\SeriesEndpoint;
use Ag84ark\SmartBill\Endpoints\StockEndpoint;
use Ag84ark\SmartBill\Endpoints\TaxesEndpoint;

class SmartBill
{
    public InvoiceEndpoint $invoiceEndpoint;
    public PaymentEndpoint $paymentEndpoint;

    public ProformaEndpoint $proformaEndpoint;

    public SeriesEndpoint $seriesEndpoint;

    public EmailEndpoint $emailEndpoint;

    public StockEndpoint $stockEndpoint;

    public TaxesEndpoint $taxesEndpoint;

    public SmartBillCloudRestClient $restClient;

    public function __construct()
    {
        $this->restClient = new SmartBillCloudRestClient(config('smartbill.username'), config('smartbill.token'));
        $this->invoiceEndpoint = new InvoiceEndpoint($this->restClient);
        $this->paymentEndpoint = new PaymentEndpoint($this->restClient);
        $this->proformaEndpoint = new ProformaEndpoint($this->restClient);
        $this->seriesEndpoint = new SeriesEndpoint($this->restClient);
        $this->emailEndpoint = new EmailEndpoint($this->restClient);
        $this->stockEndpoint = new StockEndpoint($this->restClient);
        $this->taxesEndpoint = new TaxesEndpoint($this->restClient);
    }
}
