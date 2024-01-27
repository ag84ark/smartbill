<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
use Ag84ark\SmartBill\ApiResponse\CreateProformaInvoiceResponse;
use Ag84ark\SmartBill\Resources\ProformaInvoice;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class ProformaEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    private string $seriesName;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->seriesName = config('smartbill.proformaSeries');
        parent::__construct($restClient);
    }

    public function getPDFProforma($number)
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_PDF, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_read($url, 'Accept: application/octet-stream');
    }

    public function createProforma(ProformaInvoice $invoice): CreateProformaInvoiceResponse
    {
        return $this->createProformaFromArray($invoice->toArray());
    }

    public function cancelProforma($number): BaseApiResponse
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_update($url, '');

        return BaseApiResponse::fromArray($response);
    }

    public function deleteProforma($number): BaseApiResponse
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_DELETE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_delete($url);

        return BaseApiResponse::fromArray($response);
    }

    public function restoreProforma($number): BaseApiResponse
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_RESTORE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_update($url, '');

        return BaseApiResponse::fromArray($response);
    }

    public function getStatusProforma($number): BaseApiResponse
    {
        $url = sprintf(self::STATUS_PROFORMA_URL.self::PARAMS_STATUS, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_read($url);

        return BaseApiResponse::fromArray($response);
    }

    public function createProformaFromArray(array $data): CreateProformaInvoiceResponse
    {
        return CreateProformaInvoiceResponse::fromArray($this->rest_create(self::PROFORMA_URL, $data));
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
