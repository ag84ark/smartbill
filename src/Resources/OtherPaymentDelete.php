<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Enums\PaymentTypeEnum;
use Ag84ark\SmartBill\Exceptions\InvalidPaymentTypeException;

class OtherPaymentDelete
{
    private string $companyVatCode;

    /**
     * tipul incasarii
     * valori posibile: Card, CEC, Bilet ordin, Ordin plata, Mandat postal, Alta incasare
     */
    private string $paymentType;

    /**
     * data incasarii; format AAAA-LL-ZZ
     * nu este obligatoriu daca se cunosc seria si numarul facturii
     */
    private ?string $paymentDate = null;

    /**
     * valoarea incasarii
     * nu este obligatoriu daca se cunosc seria si numarul facturii
     */
    private ?string $paymentValue = null;

    /**
     * numele clientului pentru care exista incasarea
     * nu este obligatoriu daca se cunosc seria si numarul facturii
     */
    private ?string $clientName = null;

    /**
     * CIF-ul clientului pentru care exista incasarea
     * nu este obligatoriu daca se cunosc seria si numarul facturii
     */
    private ?string $clientCif = null;

    /**
     * seria facturi pentru care este facuta incasarea
     */
    private ?string $invoiceSeries = null;

    /**
     * numarul facturi pentru care este facuta incasarea
     */
    private ?string $invoiceNumber = null;

    public function __construct()
    {
        $this->companyVatCode = config('smartbill.vatCode');
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): OtherPaymentDelete
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): OtherPaymentDelete
    {
        if(! in_array($paymentType, [
            PaymentTypeEnum::OrdinPlata,
            PaymentTypeEnum::Card,
            PaymentTypeEnum::CEC,
            PaymentTypeEnum::BiletOrdin,
            PaymentTypeEnum::MandatPostal,
            PaymentTypeEnum::Other,
        ])) {
            throw new InvalidPaymentTypeException($paymentType);
        }
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?string $paymentDate): OtherPaymentDelete
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getPaymentValue(): ?string
    {
        return $this->paymentValue;
    }

    public function setPaymentValue(?string $paymentValue): OtherPaymentDelete
    {
        $this->paymentValue = $paymentValue;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): OtherPaymentDelete
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientCif(): ?string
    {
        return $this->clientCif;
    }

    public function setClientCif(?string $clientCif): OtherPaymentDelete
    {
        $this->clientCif = $clientCif;

        return $this;
    }

    public function getInvoiceSeries(): ?string
    {
        return $this->invoiceSeries;
    }

    public function setInvoiceSeries(?string $invoiceSeries): OtherPaymentDelete
    {
        $this->invoiceSeries = $invoiceSeries;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): OtherPaymentDelete
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getUrlParams(): string
    {
        // Output: ?companyVatCode=%s&paymentType=%s&paymentDate=%s&paymentValue=%s&clientName=%s&clientCif=%s&invoiceSeries=%s&invoiceNumber=%s
        $output = '?cif='.$this->getCompanyVatCode();
        if($this->getPaymentType()) {
            $output .= '&paymentType='.$this->getPaymentType();
        }
        if($this->getPaymentDate()) {
            $output .= '&paymentDate='.$this->getPaymentDate();
        }
        if($this->getPaymentValue()) {
            $output .= '&paymentValue='.$this->getPaymentValue();
        }
        if($this->getClientName()) {
            $output .= '&clientName='.$this->getClientName();
        }
        if($this->getClientCif()) {
            $output .= '&clientCif='.$this->getClientCif();
        }
        if($this->getInvoiceSeries()) {
            $output .= '&invoiceSeries='.$this->getInvoiceSeries();
        }
        if($this->getInvoiceNumber()) {
            $output .= '&invoiceNumber='.$this->getInvoiceNumber();
        }

        return $output;

    }
}
