<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;
use Ag84ark\SmartBill\Helpers\DateHelper;

class ReverseInvoices
{
    private string $companyVatCode;
    private string $seriesName;
    private string $number;
    private string $issueDate;

    /**
     * @param string $number Invoice number to be reversed
     */
    public function __construct(string $number)
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->seriesName = config('smartbill.invoiceSeries');
        $this->issueDate = date('Y-m-d');
        $this->number = $number;
    }

    public function toArray(): array
    {
        return [
            'companyVatCode' => $this->companyVatCode,
            'seriesName' => $this->seriesName,
            'number' => $this->number,
            'issueDate' => $this->issueDate,
        ];
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): ReverseInvoices
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): ReverseInvoices
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): ReverseInvoices
    {
        $this->number = $number;

        return $this;
    }

    public function getIssueDate(): string
    {
        return $this->issueDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setIssueDate(string $issueDate): ReverseInvoices
    {
        $this->issueDate = DateHelper::getYMD($issueDate);

        return $this;
    }
}
