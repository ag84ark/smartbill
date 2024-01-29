<?php

namespace Ag84ark\SmartBill\ApiResponse;

class InvoicePaymentStatusApiResponse extends BaseApiResponse
{
    private string $number = '';
    private string $series = '';
    private float $invoiceTotalAmount = 0;
    private float $paidAmount = 0;
    private float $unpaidAmount = 0;

    public static function fromArray(array $data): InvoicePaymentStatusApiResponse
    {
        $invoicePaymentStatusResponse = new InvoicePaymentStatusApiResponse();
        $invoicePaymentStatusResponse->errorText = $data['errorText'];
        $invoicePaymentStatusResponse->message = $data['message'];
        $invoicePaymentStatusResponse->number = $data['number'];
        $invoicePaymentStatusResponse->series = $data['series'];
        $invoicePaymentStatusResponse->invoiceTotalAmount = $data['invoiceTotalAmount'];
        $invoicePaymentStatusResponse->paidAmount = $data['paidAmount'];
        $invoicePaymentStatusResponse->unpaidAmount = $data['unpaidAmount'];

        $invoicePaymentStatusResponse->responseData = $data;

        return $invoicePaymentStatusResponse;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'number' => $this->number,
            'series' => $this->series,
            'invoiceTotalAmount' => $this->invoiceTotalAmount,
            'paidAmount' => $this->paidAmount,
            'unpaidAmount' => $this->unpaidAmount,
        ]);
    }
}
