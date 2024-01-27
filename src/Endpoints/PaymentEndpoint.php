<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\Resources\OtherPaymentDelete;
use Ag84ark\SmartBill\Resources\Payment;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class PaymentEndpoint extends BaseEndpoint
{
    private string $companyVatCode;
    private string $seriesName;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->seriesName = config('smartbill.receiptSeries');
        parent::__construct($restClient);
    }

    public function createPaymentFromArray(array $data)
    {
        return $this->rest_create(self::PAYMENT_URL, $data);
    }

    public function createPayment(Payment $payment)
    {
        return $this->createPaymentFromArray($payment->toArray());
    }

    public function deleteOtherPayment(OtherPaymentDelete $paymentData)
    {
        return $this->rest_delete(self::PAYMENT_URL . $paymentData->getUrlParams(), 'DELETE');
    }

    public function cancelPayment($number)
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_update($url, '');
    }

    public function deleteReceipt($number)
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_DELETE_RECEIPT, $this->companyVatCode, $this->getEncodedSeriesName(), $number);

        return $this->rest_delete($url);
    }

    public function detailsFiscalReceipt($id)
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_FISCAL_RECEIPT, $this->companyVatCode, $id);
        $text = $this->rest_read($url);

        try {
            $text = base64_decode($text['message']);
        } catch (\Exception $ex) {
            throw new \Exception('invalid / empty response');
        }

        return $text;
    }

    private function getEncodedSeriesName(): string
    {
        return urlencode($this->seriesName);
    }
}
