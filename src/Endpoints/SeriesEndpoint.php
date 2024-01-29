<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class SeriesEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        parent::__construct($restClient);
    }

    public function getSeries(string $documentType = ''): BaseApiResponse
    {
        $documentType = ! empty($documentType) ? substr($documentType, 0, 1) : $documentType; // take the 1st character
        $url = sprintf(self::SERIES_URL, $this->companyVatCode, $documentType);
        $response = $this->rest_read($url);

        return BaseApiResponse::fromArray($response);
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): SeriesEndpoint
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }
}
