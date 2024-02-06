<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\ApiResponse\BaseApiResponse;
use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\SeriesEndpoint;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\Stubs\FakeApiResponse;
use Ag84ark\SmartBill\Tests\TestCase;

class SeriesEndpointTests extends TestCase
{
    private $restClient;
    private SeriesEndpoint $seriesEndpoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restClient = $this->createMock(SmartBillCloudRestClient::class);
        $this->seriesEndpoint = new SeriesEndpoint($this->restClient);
    }

    /** @test */
    public function it_retrieves_series_successfully()
    {
        $url = sprintf(BaseEndpoint::SERIES_URL, $this->getVatCode(), '');
        $response = FakeApiResponse::generateFakeResponse(['list' => ['name' => 'test', 'nextNumber' => 1, 'type' => 'f']]);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response->getServerResponseData());

        $apiResponse = $this->seriesEndpoint->getSeries();

        $this->assertEquals($response->toArray(), $apiResponse->toArray());
        $this->assertSame($response->toArray(), $apiResponse->toArray());
        $this->assertInstanceOf(BaseApiResponse::class, $apiResponse);
        $this->assertIsArray($apiResponse->getResponseData()['list']);

    }

    /** @test */
    public function it_retrieves_series_for_invoices_successfully()
    {
        $url = sprintf(BaseEndpoint::SERIES_URL, $this->getVatCode(), 'f');
        $response = FakeApiResponse::generateFakeResponse(['list' => ['name' => 'test', 'nextNumber' => 1, 'type' => 'f']]);

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response->getServerResponseData());

        $this->assertEquals($response->toArray(), $this->seriesEndpoint->getSeries("f")->toArray());

    }

    /** @test */
    public function it_retrieves_series_for_other_company_vat_code(): void
    {
        $this->seriesEndpoint->setCompanyVatCode('RO234');
        $this->assertEquals('RO234', $this->seriesEndpoint->getCompanyVatCode());

        $url = sprintf(BaseEndpoint::SERIES_URL, 'RO234', '');
        $response = FakeApiResponse::generateFakeResponse(['list' => ['name' => 'test', 'nextNumber' => 1, 'type' => 'f']]);
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response->getServerResponseData());

        $this->assertEquals($response->toArray(), $this->seriesEndpoint->getSeries()->toArray());
    }
}
