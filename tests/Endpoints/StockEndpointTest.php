<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\StockEndpoint;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\TestCase;

class StockEndpointTest extends TestCase
{
    private $restClient;
    private StockEndpoint $taxesEndpoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restClient = $this->createMock(SmartBillCloudRestClient::class);
        $this->taxesEndpoint = new StockEndpoint($this->restClient);
    }

    /** @test */
    public function it_retrieves_stock_successfully()
    {
        $this->taxesEndpoint->setDate('2021-01-01');
        $this->taxesEndpoint->setWarehouseName('w1');
        $this->taxesEndpoint->setProductName('p1');
        $this->taxesEndpoint->setProductCode('pc1');
        $url = sprintf(BaseEndpoint::PRODUCTS_STOCK_URL, $this->getVatCode(), '2021-01-01', 'w1', 'p1', 'pc1');
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn(['stock1', 'stock2']);

        $this->assertEquals(['stock1', 'stock2'], $this->taxesEndpoint->getProductsStock());
    }

    /** @test */
    public function it_returns_empty_when_no_stock_is_available()
    {
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->willReturn([]);

        $this->assertEquals([], $this->taxesEndpoint->getProductsStock());
    }

    /** @test */
    public function it_returns_stock_for_all_products_when_no_filters_are_set()
    {
        $this->taxesEndpoint->setDate('2021-01-01');
        $url = sprintf(BaseEndpoint::PRODUCTS_STOCK_URL, $this->getVatCode(), '2021-01-01', '', '', '');
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn(['stock1', 'stock2']);

        $this->assertEquals(['stock1', 'stock2'], $this->taxesEndpoint->getProductsStock());
    }
}
