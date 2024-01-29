<?php

namespace Ag84ark\SmartBill\ApiResponse;

class CreateProformaInvoiceApiResponse extends BaseApiResponse
{
    private string $number = '';

    private string $series = '';

    private string $url = '';

    public static function fromArray(array $data): CreateProformaInvoiceApiResponse
    {
        $proformaInvoiceResponse = new CreateProformaInvoiceApiResponse();
        $proformaInvoiceResponse->errorText = $data['errorText'];
        $proformaInvoiceResponse->message = $data['message'];
        $proformaInvoiceResponse->number = $data['number'];
        $proformaInvoiceResponse->series = $data['series'];
        $proformaInvoiceResponse->url = $data['url'];
        $proformaInvoiceResponse->responseData = $data;

        return $proformaInvoiceResponse;
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
