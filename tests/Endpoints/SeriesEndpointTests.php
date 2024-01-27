<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\SeriesEndpoint;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
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
        $response = ['sbcSeries' => ['list' => ['name' => 'test', 'nextNumber' => 1, 'type' => 'f']]];

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response);

        $this->assertEquals($response, $this->seriesEndpoint->getSeries());

    }

    /** @test */
    public function it_retrieves_series_for_invoices_successfully()
    {
        $url = sprintf(BaseEndpoint::SERIES_URL, $this->getVatCode(), 'f');
        $response = ['sbcSeries' => ['list' => ['name' => 'test', 'nextNumber' => 1, 'type' => 'f']]];

        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response);

        $this->assertEquals($response, $this->seriesEndpoint->getSeries("f"));

    }
}
