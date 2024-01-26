<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;
use Ag84ark\SmartBill\Helpers\DateHelper;
use Illuminate\Support\Collection;

class Invoice
{
    private string $companyVatCode;
    private Client $client;

    /** Incasarea facturii la emitere */
    private ?InvoicePayment $payment = null;
    private string $issueDate;
    private string $seriesName;
    private string $dueDate;
    private ?string $deliveryDate = null;

    private bool $isDraft = false;

    private string $currency = 'RON';

    private float $exchangeRate = 1;

    private string $language = 'RO';

    private int $precision = 2; // 2, 3 sau 4

    private ?string $issuerCnp = null;

    private ?string $issuerName = null;

    /** se foloseste cand se factureaza un aviz */
    private ?string $aviz = null;

    private ?string $mentions = null;

    /** aceste informatii nu vor fi trecute pe factura*/
    private ?string $observations = null;

    private ?string $delegateName = null;

    /** apare pe factura doar cand se trimite si delegateName*/
    private ?string $delegateIdentityCard = null;

    /** apare pe factura doar cand se trimit delegateName si delegateIdentityCard*/
    private ?string $delegateAuto = null;

    private ?string $paymentDate = null;

    private bool $useStock = false;

    /**
     * true - se emite factura pe baza de proforma
     * false - se emite factura pe baza parametrilor pentru emitere
     * default: false
     */
    private bool $useEstimateDetails = false;

    /**
     * true - se foloseste tva la incasare;
     * false  - nu se foloseste tva la incasare
     * default: false
     */
    private bool $usePaymentTax = false;

    /** valoare baza incasata */
    private float $paymentBase = 0;

    /** valoare tva incasat */
    private float $colectedTax = 0;

    /** total valoare incasata = valoare baza incasata + valoare tva incasat
     * default: 0 */
    private float $paymentTotal = 0;

    /** se completeaza doar pentru afisarea linkului de plata pe pdf */
    private ?string $paymentUrl = null;

    /** numarul proformei */
    private ?string $number = null;

    /** Trimiterea facturii clientului la emitere utilizand $email data de mai jos */
    private bool $sendEmail = false;

    /** adresele de email la care se trimite factura */
    private ?InvoiceSendEmailDetails $email = null;


    /**
     * @var InvoiceProduct[]|Collection
     */
    private Collection $products;

    public function __construct()
    {
        $this->companyVatCode = config('smartbill.vatCode');
        $this->seriesName = config('smartbill.invoiceSeries');
        $this->dueDate = date('Y-m-d', time() + config('smartbill.defaultDueDays') * 86400);
        $this->products = collect();
        $this->issueDate = date('Y-m-d');
    }

    public static function make(): self
    {
        return new self();
    }

    public static function makeProforma(string $number): self
    {
        $invoice = new self();
        $invoice->seriesName = config('smartbill.proformaSeries');
        $invoice->number = $number;

        return $invoice;
    }

    public static function makeDraft(): self
    {
        $invoice = new self();
        $invoice->isDraft = true;

        return $invoice;
    }

    public function addProduct(InvoiceProduct $product)
    {
        $this->products->push($product);
    }

    public function toArray(): array
    {
        return collect([
            'companyVatCode' => $this->companyVatCode,
            'client' => $this->client->toArray(),
            'payment' => $this->payment ? $this->payment->toArray() : null,
            'issueDate' => $this->issueDate,
            'seriesName' => $this->seriesName,
            'dueDate' => $this->dueDate,
            'deliveryDate' => $this->deliveryDate,
            'isDraft' => $this->isDraft,
            'currency' => $this->currency,
            'exchangeRate' => $this->exchangeRate,
            'language' => $this->language,
            'precision' => $this->precision,
            'issuerCnp' => $this->issuerCnp,
            'issuerName' => $this->issuerName,
            'aviz' => $this->aviz,
            'mentions' => $this->mentions,
            'observations' => $this->observations,
            'delegateName' => $this->delegateName,
            'delegateIdentityCard' => $this->delegateIdentityCard,
            'delegateAuto' => $this->delegateAuto,
            'paymentDate' => $this->paymentDate,
            'useStock' => $this->useStock,
            'useEstimateDetails' => $this->useEstimateDetails,
            'usePaymentTax' => $this->usePaymentTax,
            'paymentBase' => $this->paymentBase,
            'colectedTax' => $this->colectedTax,
            'paymentTotal' => $this->paymentTotal,
            'paymentUrl' => $this->paymentUrl,
            'number' => $this->number,
            'sendEmail' => $this->sendEmail,
            'email' => $this->email ? $this->email->toArray() : null,
            'products' => $this->products->map(function (InvoiceProduct $product) {
                return $product->toArray();
            })->toArray(),
        ])->filter(function ($value) {
            return ! is_null($value);
        })->toArray();
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): Invoice
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): Invoice
    {
        $this->client = $client;

        return $this;
    }

    public function getPayment(): ?InvoicePayment
    {
        return $this->payment;
    }

    public function setPayment(?InvoicePayment $payment): Invoice
    {
        $this->payment = $payment;

        return $this;
    }

    public function getIssueDate(): string
    {
        return $this->issueDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setIssueDate(string $issueDate): Invoice
    {
        $this->issueDate = DateHelper::getYMD($issueDate);

        return $this;
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): Invoice
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    public function getDueDate(): string
    {
        return $this->dueDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setDueDate(string $dueDate): Invoice
    {
        $this->dueDate = DateHelper::getYMD($dueDate);

        return $this;
    }

    public function getDeliveryDate(): ?string
    {
        return $this->deliveryDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setDeliveryDate(?string $deliveryDate): Invoice
    {
        $this->deliveryDate = $deliveryDate ? DateHelper::getYMD($deliveryDate) : null;

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): Invoice
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;

        return $this;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): Invoice
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): Invoice
    {
        $this->language = $language;

        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): Invoice
    {
        if(! in_array($precision, [2, 3, 4])) {
            throw new \InvalidArgumentException('Precision must be 2, 3 or 4');
        }

        $this->precision = $precision;

        return $this;
    }

    public function getIssuerCnp(): ?string
    {
        return $this->issuerCnp;
    }

    public function setIssuerCnp(?string $issuerCnp): Invoice
    {
        $this->issuerCnp = $issuerCnp;

        return $this;
    }

    public function getIssuerName(): ?string
    {
        return $this->issuerName;
    }

    public function setIssuerName(?string $issuerName): Invoice
    {
        $this->issuerName = $issuerName;

        return $this;
    }

    public function getAviz(): ?string
    {
        return $this->aviz;
    }

    public function setAviz(?string $aviz): Invoice
    {
        $this->aviz = $aviz;

        return $this;
    }

    public function getMentions(): ?string
    {
        return $this->mentions;
    }

    public function setMentions(?string $mentions): Invoice
    {
        $this->mentions = $mentions;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): Invoice
    {
        $this->observations = $observations;

        return $this;
    }

    public function getDelegateName(): ?string
    {
        return $this->delegateName;
    }

    public function setDelegateName(?string $delegateName): Invoice
    {
        $this->delegateName = $delegateName;

        return $this;
    }

    public function getDelegateIdentityCard(): ?string
    {
        return $this->delegateIdentityCard;
    }

    public function setDelegateIdentityCard(?string $delegateIdentityCard): Invoice
    {
        $this->delegateIdentityCard = $delegateIdentityCard;

        return $this;
    }

    public function getDelegateAuto(): ?string
    {
        return $this->delegateAuto;
    }

    public function setDelegateAuto(?string $delegateAuto): Invoice
    {
        $this->delegateAuto = $delegateAuto;

        return $this;
    }

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setPaymentDate(?string $paymentDate): Invoice
    {
        $this->paymentDate = $paymentDate ? DateHelper::getYMD($paymentDate) : null;

        return $this;
    }

    public function isUseStock(): bool
    {
        return $this->useStock;
    }

    public function setUseStock(bool $useStock): Invoice
    {
        $this->useStock = $useStock;

        return $this;
    }

    public function isUseEstimateDetails(): bool
    {
        return $this->useEstimateDetails;
    }

    public function setUseEstimateDetails(bool $useEstimateDetails): Invoice
    {
        $this->useEstimateDetails = $useEstimateDetails;

        return $this;
    }

    public function isUsePaymentTax(): bool
    {
        return $this->usePaymentTax;
    }

    public function setUsePaymentTax(bool $usePaymentTax): Invoice
    {
        $this->usePaymentTax = $usePaymentTax;

        return $this;
    }

    public function getPaymentBase(): float
    {
        return $this->paymentBase;
    }

    public function setPaymentBase(float $paymentBase): Invoice
    {
        $this->paymentBase = $paymentBase;

        return $this;
    }

    public function getColectedTax(): float
    {
        return $this->colectedTax;
    }

    public function setColectedTax(float $colectedTax): Invoice
    {
        $this->colectedTax = $colectedTax;

        return $this;
    }

    public function getPaymentTotal(): float
    {
        return $this->paymentTotal;
    }

    public function setPaymentTotal(float $paymentTotal): Invoice
    {
        $this->paymentTotal = $paymentTotal;

        return $this;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): Invoice
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): Invoice
    {
        $this->number = $number;

        return $this;
    }

    public function isSendEmail(): bool
    {
        return $this->sendEmail;
    }

    public function setSendEmail(bool $sendEmail): Invoice
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    public function getEmail(): ?InvoiceSendEmailDetails
    {
        return $this->email;
    }

    public function setEmail(?InvoiceSendEmailDetails $email): Invoice
    {
        $this->email = $email;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): Invoice
    {
        $this->products = $products;

        return $this;
    }
}
