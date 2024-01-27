<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\TaxesEndpoint;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\Stubs\FakeApiResponse;
use Ag84ark\SmartBill\Tests\TestCase;

class TaxesEndpointTest extends TestCase
{
    private $restClient;
    private $taxesEndpoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restClient = $this->createMock(SmartBillCloudRestClient::class);
        $this->taxesEndpoint = new TaxesEndpoint($this->restClient);
    }

    /** @test */
    public function it_retrieves_taxes_successfully()
    {
        $url = sprintf(BaseEndpoint::TAXES_URL, $this->getVatCode());
        $response = FakeApiResponse::generateFakeResponse(['taxes' => ['name' => 'Redusa', 'percentage' => 9]]);
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response->getServerResponseData());

        $this->assertEquals(['name' => 'Redusa', 'percentage' => 9], $this->taxesEndpoint->getTaxes()->getResponseData()['taxes']);
    }

    /** @test */
    public function it_returns_empty_when_no_taxes_are_available()
    {
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->willReturn(FakeApiResponse::generateFakeResponse()->getServerResponseData());

        $this->taxesEndpoint->getTaxes();
    }
}
