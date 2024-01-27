<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidDateException;
use Ag84ark\SmartBill\Helpers\DateHelper;
use Illuminate\Support\Collection;

class ProformaInvoice
{
    private string $companyVatCode;
    private Client $client;
    private string $issueDate;
    private string $seriesName;
    private string $dueDate;

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

    /** se completeaza doar pentru afisarea linkului de plata pe pdf */
    private ?string $paymentUrl = null;

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
            'issueDate' => $this->issueDate,
            'seriesName' => $this->seriesName,
            'dueDate' => $this->dueDate,
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
            'paymentUrl' => $this->paymentUrl,
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

    public function setCompanyVatCode(string $companyVatCode): ProformaInvoice
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): ProformaInvoice
    {
        $this->client = $client;

        return $this;
    }

    public function getIssueDate(): string
    {
        return $this->issueDate;
    }

    /**
     * @throws InvalidDateException
     */
    public function setIssueDate(string $issueDate): ProformaInvoice
    {
        $this->issueDate = DateHelper::getYMD($issueDate);

        return $this;
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): ProformaInvoice
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
    public function setDueDate(string $dueDate): ProformaInvoice
    {
        $this->dueDate = DateHelper::getYMD($dueDate);

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): ProformaInvoice
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): ProformaInvoice
    {
        $this->currency = $currency;

        return $this;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): ProformaInvoice
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): ProformaInvoice
    {
        $this->language = $language;

        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ProformaInvoice
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

    public function setIssuerCnp(?string $issuerCnp): ProformaInvoice
    {
        $this->issuerCnp = $issuerCnp;

        return $this;
    }

    public function getIssuerName(): ?string
    {
        return $this->issuerName;
    }

    public function setIssuerName(?string $issuerName): ProformaInvoice
    {
        $this->issuerName = $issuerName;

        return $this;
    }

    public function getAviz(): ?string
    {
        return $this->aviz;
    }

    public function setAviz(?string $aviz): ProformaInvoice
    {
        $this->aviz = $aviz;

        return $this;
    }

    public function getMentions(): ?string
    {
        return $this->mentions;
    }

    public function setMentions(?string $mentions): ProformaInvoice
    {
        $this->mentions = $mentions;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): ProformaInvoice
    {
        $this->observations = $observations;

        return $this;
    }

    public function getDelegateName(): ?string
    {
        return $this->delegateName;
    }

    public function setDelegateName(?string $delegateName): ProformaInvoice
    {
        $this->delegateName = $delegateName;

        return $this;
    }

    public function getDelegateIdentityCard(): ?string
    {
        return $this->delegateIdentityCard;
    }

    public function setDelegateIdentityCard(?string $delegateIdentityCard): ProformaInvoice
    {
        $this->delegateIdentityCard = $delegateIdentityCard;

        return $this;
    }

    public function getDelegateAuto(): ?string
    {
        return $this->delegateAuto;
    }

    public function setDelegateAuto(?string $delegateAuto): ProformaInvoice
    {
        $this->delegateAuto = $delegateAuto;

        return $this;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): ProformaInvoice
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): ProformaInvoice
    {
        $this->products = $products;

        return $this;
    }
}
