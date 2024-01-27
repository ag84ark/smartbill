<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
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

    public function createPaymentFromArray(array $data): BaseApiResponse
    {
        $response = $this->rest_create(self::PAYMENT_URL, $data);

        return BaseApiResponse::fromArray($response);
    }

    public function createPayment(Payment $payment): BaseApiResponse
    {
        $response = $this->rest_create(self::PAYMENT_URL, $payment->toArray());

        return BaseApiResponse::fromArray($response);
    }

    public function deleteOtherPayment(OtherPaymentDelete $paymentData): BaseApiResponse
    {
        $response = $this->rest_delete(self::PAYMENT_URL . $paymentData->getUrlParams(), 'DELETE');

        return BaseApiResponse::fromArray($response);
    }

    public function cancelPayment($number): BaseApiResponse
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_CANCEL, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_update($url, '');

        return BaseApiResponse::fromArray($response);
    }

    public function deleteReceipt($number): BaseApiResponse
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_DELETE_RECEIPT, $this->companyVatCode, $this->getEncodedSeriesName(), $number);
        $response = $this->rest_delete($url);

        return BaseApiResponse::fromArray($response);
    }

    public function detailsFiscalReceipt($id): BaseApiResponse
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_FISCAL_RECEIPT, $this->companyVatCode, $id);
        $response = $this->rest_read($url);

        try {
            $response['message'] = base64_decode($response['message']);

            return BaseApiResponse::fromArray($response);
        } catch (\Exception $ex) {
            throw new \Exception('invalid / empty response');
        }
    }

    private function getEncodedSeriesName(): string
    {
        return urlencode($this->seriesName);
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): PaymentEndpoint
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): PaymentEndpoint
    {
        $this->seriesName = $seriesName;

        return $this;
    }
}
