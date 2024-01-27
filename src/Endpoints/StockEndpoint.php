<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\Helpers\DateHelper;
use Ag84ark\SmartBill\SmartBillCloudRestClient;

class StockEndpoint extends BaseEndpoint
{
    private string $companyVatCode;

    private string $date;

    private string $warehouseName = '';

    private string $productName = '';

    private string $productCode = '';

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        parent::__construct($restClient);
        $this->companyVatCode = config('smartbill.vatCode');
        $this->date = date('Y-m-d');
    }

    public function getProductsStock()
    {
        $url = sprintf(self::PRODUCTS_STOCK_URL, $this->companyVatCode, $this->date, $this->warehouseName, $this->productName, $this->productCode);

        return $this->rest_list($url);
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): StockEndpoint
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): StockEndpoint
    {
        $this->date = DateHelper::getYMD($date);

        return $this;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(string $warehouseName): StockEndpoint
    {
        $this->warehouseName = $warehouseName;

        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): StockEndpoint
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): StockEndpoint
    {
        $this->productCode = $productCode;

        return $this;
    }
}
