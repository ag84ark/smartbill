<?php

namespace Ag84ark\SmartBill\ApiResponse;

class CreateInvoiceApiResponse extends BaseApiResponse
{
    private string $number = '';

    private string $series = '';

    private string $url = '';

    public static function fromArray(array $data): CreateInvoiceApiResponse
    {
        $invoiceResponse = new CreateInvoiceApiResponse();
        $invoiceResponse->errorText = $data['errorText'];
        $invoiceResponse->message = $data['message'];
        $invoiceResponse->number = $data['number'];
        $invoiceResponse->series = $data['series'];
        $invoiceResponse->url = $data['url'];
        $invoiceResponse->responseData = $data;

        return $invoiceResponse;
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
        return array_merge(parent::toArray(), [
            'number' => $this->number,
            'series' => $this->series,
            'url' => $this->url,
        ]);
    }
}
