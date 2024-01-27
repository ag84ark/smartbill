<?php

namespace Ag84ark\SmartBill\Tests\Stubs;

class FakeApiResponse
{
    public string $message = '';
    public string $errorText = '';

    public array $responseData = [];

    public static function generateFakeResponse(array $data = []): FakeApiResponse
    {
        $data = array_merge(
            [
                'errorText' => "Api response error text",
                'message' => "Api response message",
            ],
            $data
        );


        return FakeApiResponse::fromArray($data);
    }

    public static function fromArray($data): FakeApiResponse
    {
        $invoiceResponse = new FakeApiResponse();
        $invoiceResponse->errorText = $data['errorText'];
        $invoiceResponse->message = $data['message'];

        $invoiceResponse->responseData = $data;

        return $invoiceResponse;
    }

    public function getServerResponseData(): array
    {
        return $this->responseData;
    }

    public function toArray(): array
    {
        return [
            'errorText' => $this->errorText,
            'message' => $this->message,
            'responseData' => $this->responseData,
        ];

    }
}
