<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidPaymentTypeException;

class InvoicePayment
{

    private array $paymentTypes = [
        'Chitanta',
        'Bon',
        'Ordin de plata',
        'Card',
        'CEC',
        'Bilet ordin',
        'Mandat postal',
        'Alta',
    ];

    private string $type;
    private float  $value;

    private bool $isCash = false;

    private ?string $paymentSeries = null;

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @throws InvalidPaymentTypeException
     */
    public function setType(string $type): InvoicePayment
    {
        if (!in_array($type, $this->paymentTypes)) {
            throw new InvalidPaymentTypeException($type);
        }
        $this->type = $type;
        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): InvoicePayment
    {
        $this->value = $value;
        return $this;
    }

    public function isCash(): bool
    {
        return $this->isCash;
    }

    public function setIsCash(bool $isCash): InvoicePayment
    {
        $this->isCash = $isCash;
        return $this;
    }

    public function getPaymentSeries(): ?string
    {
        return $this->paymentSeries;
    }

    public function setPaymentSeries(?string $paymentSeries): InvoicePayment
    {
        $this->paymentSeries = $paymentSeries;
        return $this;
    }

    public function toArray(): array
    {
        return collect([
            'type' => $this->type,
            'value' => $this->value,
            'isCash' => $this->isCash,
            'paymentSeries' => $this->paymentSeries,
        ])->filter(function ($value) {
            return !is_null($value);
        })->toArray();
    }

}