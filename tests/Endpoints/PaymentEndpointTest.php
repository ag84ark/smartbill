<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\PaymentEndpoint;
use Ag84ark\SmartBill\Enums\PaymentTypeEnum;
use Ag84ark\SmartBill\Resources\OtherPaymentDelete;
use Ag84ark\SmartBill\Resources\Payment;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\Stubs\FakeApiResponse;
use Ag84ark\SmartBill\Tests\TestCase;

class PaymentEndpointTest extends TestCase
{
    private PaymentEndpoint $paymentEndpoint;

    /**
     * @var SmartBillCloudRestClient|\PHPUnit\Framework\MockObject\MockObject
     */
    private $restClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->restClient = $this->createMock(SmartBillCloudRestClient::class);

        $this->paymentEndpoint = new PaymentEndpoint($this->restClient);
    }

    /**
     * Test createPaymentFromArray method
     */
    public function testCreatePaymentFromArray(): void
    {
        // Prepare data for the test
        $paymentArray = [
            'companyVatCode' => config('smartbill.vatCode'),
            'value' => 100,
        ];

        $responseData = FakeApiResponse::generateFakeResponse();

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with($this->equalTo(SmartBillCloudRestClient::HTTP_POST), $this->equalTo(BaseEndpoint::PAYMENT_URL), $this->equalTo($paymentArray))
            ->willReturn($responseData->getServerResponseData());

        $this->assertSame($responseData->toArray(), $this->paymentEndpoint->createPaymentFromArray($paymentArray)->toArray());
    }

    /**
     * @test
     */
    public function it_can_create_payment()
    {

        $payment = $this->createMock(Payment::class);
        $payment->method('toArray')
            ->willReturn(['key' => 'value']);

        $this->paymentEndpoint->setCompanyVatCode('RO123456789');
        $this->paymentEndpoint->setSeriesName('SSS');
        $this->assertEquals('RO123456789', $this->paymentEndpoint->getCompanyVatCode());
        $this->assertEquals('SSS', $this->paymentEndpoint->getSeriesName());


        $responseData = FakeApiResponse::generateFakeResponse(['number' => '0001', 'series' => 'TEST-INV']);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(
                $this->equalTo(SmartBillCloudRestClient::HTTP_POST),
                $this->equalTo(BaseEndpoint::PAYMENT_URL),
                $this->equalTo($payment->toArray())
            )
            ->willReturn($responseData->getServerResponseData());

        $this->assertSame($responseData->toArray(), $this->paymentEndpoint->createPayment($payment)->toArray());
    }

    /** @test */
    public function delete_other_payment()
    {

        $paymentData = new OtherPaymentDelete();
        $paymentData->setCompanyVatCode('RO12345678')
            ->setPaymentType(PaymentTypeEnum::OrdinPlata)
            ->setPaymentDate('2023-01-01')
            ->setPaymentValue('200')
            ->setClientName('John Doe')
            ->setClientCif('RO87654321')
            ->setInvoiceSeries('A')
            ->setInvoiceNumber('1');

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(
                $this->equalTo(SmartBillCloudRestClient::HTTP_DELETE),
                $this->equalTo(BaseEndpoint::PAYMENT_URL . $paymentData->getUrlParams()),
            )
            ->willReturn(FakeApiResponse::generateFakeResponse()->getServerResponseData());

        $this->paymentEndpoint->deleteOtherPayment($paymentData);

    }

    /** @test */
    public function it_cancels_a_payment(): void
    {
        $mockPaymentNumber = '12345';
        $mockCancelResponse = FakeApiResponse::generateFakeResponse();

        $expectedUrl = sprintf(BaseEndpoint::PAYMENT_URL . BaseEndpoint::PARAMS_CANCEL, config('smartbill.vatCode'), urlencode(config('smartbill.receiptSeries')), $mockPaymentNumber);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_PUT, $expectedUrl)
            ->willReturn($mockCancelResponse->getServerResponseData());

        $cancelResponse = $this->paymentEndpoint->cancelPayment($mockPaymentNumber);

        $this->assertSame($mockCancelResponse->toArray(), $cancelResponse->toArray());
    }

    /** @test */
    public function it_cancels_a_payment_for_other_company_vat_code(): void
    {
        $mockPaymentNumber = '12345';
        $mockCancelResponse = FakeApiResponse::generateFakeResponse();

        $this->paymentEndpoint->setCompanyVatCode('RO123456789');
        $this->paymentEndpoint->setSeriesName('SSS');
        $this->assertEquals('RO123456789', $this->paymentEndpoint->getCompanyVatCode());
        $this->assertEquals('SSS', $this->paymentEndpoint->getSeriesName());

        $expectedUrl = sprintf(BaseEndpoint::PAYMENT_URL . BaseEndpoint::PARAMS_CANCEL, 'RO123456789', urlencode('SSS'), $mockPaymentNumber);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_PUT, $expectedUrl)
            ->willReturn($mockCancelResponse->getServerResponseData());

        $cancelResponse = $this->paymentEndpoint->cancelPayment($mockPaymentNumber);

        $this->assertSame($mockCancelResponse->toArray(), $cancelResponse->toArray());
    }

    public function testCancelPayment(): void
    {
        $mockPaymentNumber = '12345';
        $mockCancelResponse = FakeApiResponse::generateFakeResponse();

        $expectedUrl = sprintf(BaseEndpoint::PAYMENT_URL . BaseEndpoint::PARAMS_CANCEL, config('smartbill.vatCode'), urlencode(config('smartbill.receiptSeries')), $mockPaymentNumber);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_PUT, $expectedUrl)
            ->willReturn($mockCancelResponse->getServerResponseData());

        $cancelResponse = $this->paymentEndpoint->cancelPayment($mockPaymentNumber);

        $this->assertSame($mockCancelResponse->toArray(), $cancelResponse->toArray());
    }

    /** @test */
    public function it_can_delete_a_receipt(): void
    {
        $mockReceiptNumber = '12345';
        $mockDeleteResponse = FakeApiResponse::generateFakeResponse();

        $expectedUrl = sprintf(BaseEndpoint::PAYMENT_URL . BaseEndpoint::PARAMS_DELETE_RECEIPT, config('smartbill.vatCode'), urlencode(config('smartbill.receiptSeries')), $mockReceiptNumber);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_DELETE, $expectedUrl)
            ->willReturn($mockDeleteResponse->getServerResponseData());

        $deleteResponse = $this->paymentEndpoint->deleteReceipt($mockReceiptNumber);

        $this->assertSame($mockDeleteResponse->toArray(), $deleteResponse->toArray());
    }

    /** @test */
    public function it_gets_details_fiscal_receipt(): void
    {
        $mockReceiptId = '12345';
        $mockDetailsResponse = FakeApiResponse::generateFakeResponse(['message' => base64_encode('Some message')]);

        $expectedUrl = sprintf(BaseEndpoint::PAYMENT_URL . BaseEndpoint::PARAMS_FISCAL_RECEIPT, config('smartbill.vatCode'), $mockReceiptId);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $expectedUrl)
            ->willReturn($mockDetailsResponse->getServerResponseData());

        $detailsResponse = $this->paymentEndpoint->detailsFiscalReceipt($mockReceiptId);

        $this->assertSame(base64_decode($mockDetailsResponse->toArray()['message']), $detailsResponse->toArray()['message']);
    }
}
