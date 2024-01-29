<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class TaxesEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        parent::__construct($restClient);
    }

    public function getTaxes(): BaseApiResponse
    {
        $url = sprintf(self::TAXES_URL, $this->companyVatCode);
        $response = $this->rest_read($url);

        return BaseApiResponse::fromArray($response);
    }

    public function setCompanyVatCode(string $companyVatCode): TaxesEndpoint
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }
}
