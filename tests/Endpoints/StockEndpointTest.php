<?php

namespace Ag84ark\SmartBill\Tests\Endpoints;

use Ag84ark\SmartBill\Endpoints\BaseEndpoint;
use Ag84ark\SmartBill\Endpoints\StockEndpoint;
use Ag84ark\SmartBill\SmartBillCloudRestClient;
use Ag84ark\SmartBill\Tests\Stubs\FakeApiResponse;
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

        $this->assertEquals('2021-01-01', $this->taxesEndpoint->getDate());
        $this->assertEquals('w1', $this->taxesEndpoint->getWarehouseName());
        $this->assertEquals('p1', $this->taxesEndpoint->getProductName());
        $this->assertEquals('pc1', $this->taxesEndpoint->getProductCode());

        $url = sprintf(BaseEndpoint::PRODUCTS_STOCK_URL, $this->getVatCode(), '2021-01-01', 'w1', 'p1', 'pc1');
        $response = FakeApiResponse::generateFakeResponse([
            'list' =>
                ['products' =>
                    [
                        "measuringUnit" => "buc",
                        "productCode" => "IT001",
                        "productName" => "Revista IT",
                        "quantity" => "100",
                    ],
                ],
            ]);
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn($response->getServerResponseData());

        $apiResponse = $this->taxesEndpoint->getProductsStock();
        $this->assertEquals($apiResponse->toArray(), $response->toArray());

        $this->assertIsArray($apiResponse->getResponseData()['list']['products']);
    }

    /** @test */
    public function it_returns_stock_for_all_products_when_no_filters_are_set()
    {
        $this->taxesEndpoint->setDate('2021-01-01');
        $url = sprintf(BaseEndpoint::PRODUCTS_STOCK_URL, $this->getVatCode(), '2021-01-01', '', '', '');
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn(FakeApiResponse::generateFakeResponse()->getServerResponseData());

        $this->taxesEndpoint->getProductsStock();
    }

    /** @test */
    public function can_use_a_different_company_vat_code()
    {
        $this->taxesEndpoint->setCompanyVatCode('RO123');
        $this->assertEquals('RO123', $this->taxesEndpoint->getCompanyVatCode());
        $this->taxesEndpoint->setDate('2021-01-01');

        $url = sprintf(BaseEndpoint::PRODUCTS_STOCK_URL, 'RO123', '2021-01-01', '', '', '');
        $this->restClient->expects($this->once())
            ->method('performHttpCall')
            ->with(SmartBillCloudRestClient::HTTP_GET, $url)
            ->willReturn(FakeApiResponse::generateFakeResponse()->getServerResponseData());

        $this->taxesEndpoint->getProductsStock();
    }
}
