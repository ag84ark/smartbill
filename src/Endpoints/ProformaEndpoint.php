<?php

namespace Ag84ark\SmartBill\Endpoints;

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

    public function createProforma(ProformaInvoice $invoice)
    {
        return $this->createProformaFromArray($invoice->toArray());
    }

    public function cancelProforma($number)
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_update($url, '');
    }

    public function deleteProforma($number)
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_DELETE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_delete($url);
    }

    public function restoreProforma($number)
    {
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_RESTORE, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_update($url, '', 'PUT');
    }

    public function getStatusProforma($number)
    {
        $url = sprintf(self::STATUS_PROFORMA_URL.self::PARAMS_STATUS, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_read($url);
    }

    public function createProformaFromArray(array $data)
    {
        return $this->rest_create(self::PROFORMA_URL, $data);
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
