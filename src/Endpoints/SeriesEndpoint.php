<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\SmartBillCloudRestClient;

class SeriesEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        parent::__construct($restClient);
    }

    public function getSeries(string $documentType = '')
    {
        $documentType = ! empty($documentType) ? substr($documentType, 0, 1) : $documentType; // take the 1st character
        $url = sprintf(self::SERIES_URL, $this->companyVatCode, $documentType);

        return $this->rest_read($url);
    }
}
