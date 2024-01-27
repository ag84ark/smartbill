<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Enums\PaymentTypeEnum;
use Ag84ark\SmartBill\Exceptions\InvalidPaymentTypeException;

class InvoicePayment
{
    private string $type;
    private float  $value;

    private bool $isCash = false;

    private ?string $paymentSeries = null;

    public static function make(): InvoicePayment
    {
        return new self();
    }

    public static function makeCardPayment($value): InvoicePayment
    {
        return self::make()->setType(PaymentTypeEnum::Card)->setValue($value);
    }

    public static function makeCashPayment($value, $type = PaymentTypeEnum::Chitanta): InvoicePayment
    {
        return self::make()->setType($type)->setValue($value)->setIsCash(true);
    }

    public static function makeBankPayment($value): InvoicePayment
    {
        return self::make()->setType(PaymentTypeEnum::OrdinPlata)->setValue($value);
    }

    public static function makeOtherPayment($value): InvoicePayment
    {
        return self::make()->setType(PaymentTypeEnum::Other)->setValue($value);
    }

    public static function makeCECPayment($value): InvoicePayment
    {
        return self::make()->setType(PaymentTypeEnum::CEC)->setValue($value);
    }

    public static function makePostalOrderPayment($value): InvoicePayment
    {
        return self::make()->setType(PaymentTypeEnum::MandatPostal)->setValue($value);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @throws InvalidPaymentTypeException
     */
    public function setType(string $type): InvoicePayment
    {
        if (! PaymentTypeEnum::isValid($type)) {
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
            return ! is_null($value);
        })->toArray();
    }
}
