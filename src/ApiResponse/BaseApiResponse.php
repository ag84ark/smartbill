<?php

namespace Ag84ark\SmartBill\ApiResponse;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class BaseApiResponse implements Arrayable, Jsonable
{
    protected string $errorText = '';
    protected string $message = '';

    protected array $responseData = [];

    public static function fromArray(array $data): BaseApiResponse
    {
        $invoiceResponse = new BaseApiResponse();
        $invoiceResponse->errorText = $data['errorText'];
        $invoiceResponse->message = $data['message'];

        $invoiceResponse->responseData = $data;

        return $invoiceResponse;
    }

    public function toArray(): array
    {
        return [
            'errorText' => $this->errorText,
            'message' => $this->message,
            'responseData' => $this->responseData,
        ];
    }

    public function getErrorText(): string
    {
        return $this->errorText;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get all response data
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
