<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\EmailApiResponse;
use Ag84ark\SmartBill\Resources\SendEmail;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class EmailEndpoint extends BaseEndpoint
{
    public function __construct(SmartBillCloudRestClient $restClient)
    {
        parent::__construct($restClient);
    }

    public function sendEmail(SendEmail $email): EmailApiResponse
    {
        return EmailApiResponse::fromArray($this->rest_create(self::EMAIL_URL, $email->toArray()));
    }
}
