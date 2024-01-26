<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;
use Ag84ark\SmartBill\Exceptions\InvalidPaymentTypeException;
use Ag84ark\SmartBill\Helpers\DateHelper;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Payment
{
    private string $companyVatCode;

    private Client $client;

    private string $issueDate;
    private ?string $seriesName = null;

    private string $language = 'RO';
    private string $currency = 'RON';

    private float $exchangeRate = 1;

    private int $precision = 2; // 2, 3 sau 4

    /** valoarea incasarii */
    private float $value;

    /**
     * textul afisat pe incasare
     * Ex: Reprezentand contravaloare frigider Arctic
     */
    private ?string $text = null;

    private ?string $translatedText = null;

    private bool $isDraft = false;

    /**
     * Payment type
     * valori posibile: Alta incasare, Bilet ordin, Card, Card online, CEC, Chitanta, Extras de cont, Mandat postal,
     * Ordin plata, Ramburs
     */
    private string $type;

    /** Possible payment type */
    private array $paymentTypes = [
        'Chitanta',
        'Bon',
        'Ordin de plata',
        'Card',
        'Card online',
        'CEC',
        'Bilet ordin',
        'Mandat postal',
        'Alta incasare',
        'Extras de cont',
    ];

    private bool $isCash = false;

    /**
     * alte observatii in legatura cu incasarea
     * acestea sunt observatii interne care vor aparea doar in rapoartele de incasari nu si pe documente
     */
    private ?string $observations = null;

    /**
     * true - se folosesc detalii de pe factura
     * false - nu se folosesc detalii de pe factura
     * default: false
     * Se preiau de pe factura datele clientului, limba, moneda, exchangeRate si valoarea
     */
    private bool $useInvoiceDetails = false;

    /**
     * lista de facturi pentru care se emite incasarea
     * @var Collection|PaymentInvoiceListItem[]
     */
    private Collection $invoicesList;

    public function __construct()
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->invoicesList = collect();
        $this->issueDate = date('Y-m-d');
    }

    public static function make(): self
    {
        return new self();
    }

    public static function makeDraft(): self
    {
        return self::make()->setIsDraft(true);
    }

    public function toArray(): array
    {
        return collect([
            'companyVatCode' => $this->companyVatCode,
            'client' => $this->client->toArray(),
            'issueDate' => $this->issueDate,
            'seriesName' => $this->seriesName,
            'language' => $this->language,
            'currency' => $this->currency,
            'exchangeRate' => $this->exchangeRate,
            'precision' => $this->precision,
            'value' => $this->value,
            'text' => $this->text,
            'translatedText' => $this->translatedText,
            'isDraft' => $this->isDraft,
            'type' => $this->type,
            'isCash' => $this->isCash,
            'observations' => $this->observations,
            'useInvoiceDetails' => $this->useInvoiceDetails,
            'invoicesList' => $this->invoicesList->map(function (PaymentInvoiceListItem $invoiceListItem) {
                return $invoiceListItem->toArray();
            })->toArray(),
        ])->filter(function ($value) {
            return ! is_null($value);
        })->toArray();
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): self
    {
        if (! in_array($precision, [2, 3, 4])) {
            throw new InvalidArgumentException('Precision must be 2, 3 or 4');
        }

        $this->precision = $precision;

        return $this;
    }

    public function getIssueDate(): string
    {
        return $this->issueDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setIssueDate(string $issueDate): self
    {
        $this->issueDate = DateHelper::getYMD($issueDate);

        return $this;
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): Payment
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): Payment
    {
        $this->client = $client;

        return $this;
    }

    public function getSeriesName(): ?string
    {
        return $this->seriesName;
    }

    public function setSeriesName(?string $seriesName): Payment
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): Payment
    {
        $this->language = $language;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): Payment
    {
        $this->currency = $currency;

        return $this;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): Payment
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): Payment
    {
        $this->value = $value;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Payment
    {
        $this->text = $text;

        return $this;
    }

    public function getTranslatedText(): ?string
    {
        return $this->translatedText;
    }

    public function setTranslatedText(?string $translatedText): Payment
    {
        $this->translatedText = $translatedText;

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): Payment
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @throws InvalidPaymentTypeException
     */
    public function setType(string $type): Payment
    {
        if (! in_array($type, $this->paymentTypes)) {
            throw new InvalidPaymentTypeException($type);
        }
        $this->type = $type;

        return $this;
    }

    public function isCash(): bool
    {
        return $this->isCash;
    }

    public function setIsCash(bool $isCash): Payment
    {
        $this->isCash = $isCash;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): Payment
    {
        $this->observations = $observations;

        return $this;
    }

    public function isUseInvoiceDetails(): bool
    {
        return $this->useInvoiceDetails;
    }

    public function setUseInvoiceDetails(bool $useInvoiceDetails): Payment
    {
        $this->useInvoiceDetails = $useInvoiceDetails;

        return $this;
    }

    public function addInvoiceListItem(PaymentInvoiceListItem $invoiceListItem): Payment
    {
        $this->invoicesList->add($invoiceListItem);

        return $this;
    }

    public function getInvoicesList(): Collection
    {
        return $this->invoicesList;
    }

    public function setInvoicesList(Collection $invoicesList): Payment
    {
        $this->invoicesList = $invoicesList;

        return $this;
    }
}
