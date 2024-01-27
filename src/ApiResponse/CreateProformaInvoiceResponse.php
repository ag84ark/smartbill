<?php

namespace Ag84ark\SmartBill\ApiResponse;

use Illuminate\Contracts\Support\Arrayable;

class CreateProformaInvoiceResponse implements Arrayable
{
    private string $errorText = '';
    private string $message = '';

    private string $number = '';

    private string $series = '';

    private string $url = '';

    public static function fromArray(array $data): CreateProformaInvoiceResponse
    {
        $invoiceResponse = new CreateProformaInvoiceResponse();
        $invoiceResponse->errorText = $data['errorText'];
        $invoiceResponse->message = $data['message'];
        $invoiceResponse->number = $data['number'];
        $invoiceResponse->series = $data['series'];
        $invoiceResponse->url = $data['url'];

        return $invoiceResponse;
    }

    public function getErrorText(): string
    {
        return $this->errorText;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getSeries(): string
    {
        return $this->series;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function toArray(): array
    {
        return [
            'errorText' => $this->errorText,
            'message' => $this->message,
            'number' => $this->number,
            'series' => $this->series,
            'url' => $this->url,
        ];
    }
}
