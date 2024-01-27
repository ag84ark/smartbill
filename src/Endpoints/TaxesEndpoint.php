<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\SmartBillCloudRestClient;

class TaxesEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        parent::__construct($restClient);
    }

    public function getTaxes()
    {
        $url = sprintf(self::TAXES_URL, $this->companyVatCode);

        return $this->rest_read($url);
    }
}
