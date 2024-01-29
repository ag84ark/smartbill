<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
use Ag84ark\SmartBill\ApiResponse\CreateInvoiceApiResponse;
use Ag84ark\SmartBill\ApiResponse\InvoicePaymentStatusApiResponse;
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

    public function createInvoice(Invoice $invoice): CreateInvoiceApiResponse
    {
        return $this->createInvoiceFromArray($invoice->toArray());
    }

    public function createReverseInvoice(ReverseInvoices $reverseInvoices): BaseApiResponse
    {
        return $this->createReverseInvoiceFromArray($reverseInvoices->toArray());
    }

    public function deleteInvoice($number): BaseApiResponse
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_DELETE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_delete($url);

        return BaseApiResponse::fromArray($response);
    }

    public function cancelInvoice($number): BaseApiResponse
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_update($url, '');

        return BaseApiResponse::fromArray($response);
    }

    public function restoreInvoice($number): BaseApiResponse
    {
        $url = sprintf(self::INVOICE_URL.self::PARAMS_RESTORE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_update($url, '');

        return BaseApiResponse::fromArray($response);
    }

    public function getInvoicePaymentStatus($number): InvoicePaymentStatusApiResponse
    {
        $url = sprintf(self::STATUS_INVOICE_URL.self::PARAMS_STATUS, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_read($url);

        return InvoicePaymentStatusApiResponse::fromArray($response);
    }

    public function createInvoiceFromArray(array $data): CreateInvoiceApiResponse
    {
        $response = $this->rest_create(self::INVOICE_URL, $data);

        return CreateInvoiceApiResponse::fromArray($response);
    }

    public function createReverseInvoiceFromArray(array $data): BaseApiResponse
    {
        $response = $this->rest_create(self::INVOICE_REVERSE_URL, $data);

        return BaseApiResponse::fromArray($response);
    }

    public function setCompanyVatCode(string $companyVatCode): InvoiceEndpoint
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function setSeriesName(string $seriesName): InvoiceEndpoint
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    private function getEncodedSeriesName(): string
    {
        return urlencode($this->seriesName);
    }
}
