<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\Resources\Invoice;
use Ag84ark\SmartBill\Resources\ReverseInvoices;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class InvoiceEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    private string $seriesName;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->seriesName = config('smartbill.invoiceSeries');
        parent::__construct($restClient);
    }

    public function getPDFInvoice($number)
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_PDF, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_read($url,  'Accept: application/octet-stream');
    }

    public function createInvoice(Invoice $invoice)
    {
        return $this->createInvoiceFromArray($invoice->toArray());
    }

    public function createReverseInvoice(ReverseInvoices $reverseInvoices)
    {
        return $this->createReverseInvoiceFromArray($reverseInvoices->toArray());
    }

    public function deleteInvoice($number)
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_DELETE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_delete($url);
    }

    public function cancelInvoice($number)
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_update($url, '');
    }

    public function restoreInvoice($number)
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_RESTORE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_update($url, '');
    }

    public function getStatusInvoicePayments($number)
    {
        $url = sprintf(self::STATUS_INVOICE_URL.self::PARAMS_STATUS, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_read($url);
    }

    public function createInvoiceFromArray(array $data)
    {
        return $this->rest_create(self::INVOICE_URL, $data);
    }

    public function createReverseInvoiceFromArray(array $data)
    {
        return $this->rest_create(self::INVOICE_REVERSE_URL, $data);
    }

    public function setSeriesName(string $seriesName): void
    {
        $this->seriesName = $seriesName;
    }

    public function setCompanyVatCode(string $companyVatCode): void
    {
        $this->companyVatCode = $companyVatCode;
    }

    private function getEncodedSeriesName(): string
    {
        return urlencode($this->seriesName);
    }
}
