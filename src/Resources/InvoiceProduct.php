<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Exceptions\InvalidTaxNameException;

class InvoiceProduct
{
    /** numele produsului care urmeaza sa fie facturat */
    private string $name;

    /** cod produs */
    private ?string $code = null;

    /**
     * Descrierea produsului
     *
     * Se foloseste pentru introducerea pe factura a unei descrieri pentru produse/servicii
     * se poate folosi Enter pentru introducerea unei descrieri pe mai multe linii
     * */
    private ?string $productDescription;

    /** numele produsului tradus */
    private ?string $translatedName = null;

    /** traducere unitate de masura */
    private ?string $translatedMeasuringUnit = null;

    /** unitate de masura */
    private string $measuringUnitName = 'buc';

    /** moneda */
    private string $currency = 'RON';

    /** cantitate */
    private float $quantity;

    /** pretul pretul produsului
     *
     * se va folosi pretul per unitate cu/fara TVA in functie de valoarea parametrului isTaxIncluded
     */
    private float $price;

    /** true - pretul produsului contine TVA
     * false - pretul produsului nu contine TVA
     * default: true
     */
    private bool $isTaxIncluded = true;

    /** numele cotei tva Ex: Normala, Redusa, Taxare inversa, Redusa locuinte ...
     * Default din config */
    private ?string $taxName = null;

    /** procentul cotei tva Ex: 20, 9, 0, 5
     * Default din config
     */
    private ?float $taxPercentage = null;

    /** valoare curs moneda
    Default: cursul valutar oficial din ziua emiterii */
    private ?float $exchangeRate = null;

    /**
     * true sau 1 - produsul se salveaza in nomenclator
     * false sau 0 - produsul nu se salveaza in nomenclator
     * Default: false
     */
    private bool $saveToDb = false;

    /** numele gestiunii sau empty string pentru useStock=false */
    private ?string $warehouseName = null;

    /**
     * tipul de intrare pe document (produs sau serviciu)
     * Default: false
     */
    private bool $isService = false;


    /**
     * la adaugarea unui produs acest parametru are intotdeauna valoarea false;
     * in acest caz, se vor trimite parametrii referitori la produs;
     * el ia valoarea true atunci cand se adauga pe factura/bon un discount;
     * In acest caz, se vor transmite parametrii referitori la discount.
     */
    private bool $isDiscount = false;

    private ?int $numberOfItems = null;

    /**
     * 1 - discount valoric
     * 2 - discount procentual
     * Default: 2
     *
     * daca se specifica o valoare diferita de 1 sau 2, se considera discountType = 1
     */
    private ?int $discountType = null;

    /** valoare discount daca discountType este 1
     * default: 0
     *
     * valoarea trebuie sa fie negativa
     * este obligatoriu doar daca discountul este de tip valoric
    */
    private ?float $discountValue = null;

    /** procent discount daca discountType este 2
     * default: 0
     *
     * este obligatoriu doar daca discountul este de tip valoric
     */
    private ?float $discountPercentage = null;


    public function __construct()
    {
        if( config ('smartbill.companyPaysVAT') === true)
        {
            $this->taxName = config('smartbill.defaultTaxName');
            $this->taxPercentage = config('smartbill.defaultTaxPercentage');
        }
    }

    /** Returns array without null values */
    public function toArray(): array
    {
        return collect([
            'name' => $this->name,
            'code' => $this->code,
            'productDescription' => $this->productDescription,
            'translatedName' => $this->translatedName,
            'translatedMeasuringUnit' => $this->translatedMeasuringUnit,
            'isDiscount' => $this->isDiscount,
            'measuringUnitName' => $this->measuringUnitName,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'isTaxIncluded' => $this->isTaxIncluded,
            'taxName' => $this->taxName,
            'taxPercentage' => $this->taxPercentage,
            'exchangeRate' => $this->exchangeRate,
            'saveToDb' => $this->saveToDb,
            'warehouseName' => $this->warehouseName,
            'isService' => $this->isService,
        ])->filter(function ($value) {
            return !is_null($value);
        })->toArray();
    }

    public static function fromArray($data) :self
    {
        $product = new self();
        $product->setName($data['name'])
            ->setCode($data['code'] ?? '')
            ->setProductDescription($data['productDescription'] ?? null)
            ->setTranslatedName($data['translatedName'] ?? null)
            ->setTranslatedMeasuringUnit($data['translatedMeasuringUnit'] ?? null)
            ->setIsDiscount($data['isDiscount'] ?? false)
            ->setMeasuringUnitName($data['measuringUnitName'] ?? 'buc')
            ->setCurrency($data['currency'] ?? 'RON')
            ->setQuantity($data['quantity'])
            ->setPrice($data['price'])
            ->setIsTaxIncluded($data['isTaxIncluded'] ?? true)
            ->setTaxName($data['taxName'] ?? null)
            ->setTaxPercentage($data['taxPercentage'] ?? null)
            ->setExchangeRate($data['exchangeRate'] ?? null)
            ->setSaveToDb($data['saveToDb'] ?? false)
            ->setWarehouseName($data['warehouseName'] ?? null)
            ->setIsService($data['isService'] ?? false);

        return $product;
    }


    /**
     * Creeaza un discount
     *
     * @param string $name Numele discountului.
     * @param int $numberOfItems Numarul de produse la care se aplica discountul.
     * @param int $discountType 1 - discount valoric, 2 - discount procentual. Default: 2.
     * @param float $discountValue Valoarea discountului daca discountType este 1. Default: 0.
     * @param float $discountPercentage Procent discount daca discountType este 2. Default: 0.
     * @param string $measuringUnitName Unitatea de masura (optional, default is 'buc').
     * @param string $currency Moneda (optional, default is 'RON').
     * @return self Returneaza un obiect de tip InvoiceProduct.
     */
    public static function createDiscountItem(
        string $name,
        int $numberOfItems,
        int $discountType = 2,
        float $discountValue = 0,
        float $discountPercentage = 0,
        string $measuringUnitName = 'buc' ,
        string $currency = 'RON'
    ) :self
    {
        $product = new self();
        $product->setIsDiscount(true);
        $product->setName($name);
        $product->setNumberOfItems($numberOfItems);
        $product->setDiscountType($discountType);
        $product->setDiscountValue($discountValue);
        $product->setDiscountPercentage($discountPercentage);
        $product->setMeasuringUnitName($measuringUnitName);
        $product->setCurrency($currency);

        return $product;

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): InvoiceProduct
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): InvoiceProduct
    {
        $this->code = $code;
        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(?string $productDescription): InvoiceProduct
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    public function getTranslatedName(): ?string
    {
        return $this->translatedName;
    }

    public function setTranslatedName(?string $translatedName): InvoiceProduct
    {
        $this->translatedName = $translatedName;
        return $this;
    }

    public function getTranslatedMeasuringUnit(): ?string
    {
        return $this->translatedMeasuringUnit;
    }

    public function setTranslatedMeasuringUnit(?string $translatedMeasuringUnit): InvoiceProduct
    {
        $this->translatedMeasuringUnit = $translatedMeasuringUnit;
        return $this;
    }

    public function isDiscount(): bool
    {
        return $this->isDiscount;
    }

    public function setIsDiscount(bool $isDiscount): InvoiceProduct
    {
        $this->isDiscount = $isDiscount;
        return $this;
    }

    public function getMeasuringUnitName(): string
    {
        return $this->measuringUnitName;
    }

    public function setMeasuringUnitName(string $measuringUnitName): InvoiceProduct
    {
        $this->measuringUnitName = $measuringUnitName;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): InvoiceProduct
    {
        $this->currency = $currency;
        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): InvoiceProduct
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): InvoiceProduct
    {
        $this->price = $price;
        return $this;
    }

    public function isTaxIncluded(): bool
    {
        return $this->isTaxIncluded;
    }

    public function setIsTaxIncluded(bool $isTaxIncluded): InvoiceProduct
    {
        $this->isTaxIncluded = $isTaxIncluded;
        return $this;
    }

    public function getTaxName(): ?string
    {
        return $this->taxName;
    }

    /**
     * @throws InvalidTaxNameException
     */
    public function setTaxName(?string $taxName): InvoiceProduct
    {
        if(!$taxName && !in_array($taxName, config('smartbill.taxNames')))
        {
            throw new InvalidTaxNameException($taxName);
        }
        $this->taxName = $taxName;
        return $this;
    }

    public function getTaxPercentage(): ?float
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage(?float $taxPercentage): InvoiceProduct
    {
        $this->taxPercentage = $taxPercentage;
        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(?float $exchangeRate): InvoiceProduct
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    public function isSaveToDb(): bool
    {
        return $this->saveToDb;
    }

    public function setSaveToDb(bool $saveToDb): InvoiceProduct
    {
        $this->saveToDb = $saveToDb;
        return $this;
    }

    public function getWarehouseName(): ?string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(?string $warehouseName): InvoiceProduct
    {
        $this->warehouseName = $warehouseName;
        return $this;
    }

    public function isService(): bool
    {
        return $this->isService;
    }

    public function setIsService(bool $isService): InvoiceProduct
    {
        $this->isService = $isService;
        return $this;
    }

    public function getNumberOfItems(): ?int
    {
        return $this->numberOfItems;
    }

    public function setNumberOfItems(?int $numberOfItems): InvoiceProduct
    {
        $this->numberOfItems = $numberOfItems;
        return $this;
    }

    public function getDiscountType(): ?int
    {
        return $this->discountType;
    }

    public function setDiscountType(?int $discountType): InvoiceProduct
    {
        $this->discountType = $discountType;
        return $this;
    }

    public function getDiscountValue(): ?float
    {
        return $this->discountValue;
    }

    public function setDiscountValue(?float $discountValue): InvoiceProduct
    {
        if($discountValue > 0)
        {
            $discountValue = -$discountValue;
        }

        $this->discountValue = $discountValue;
        return $this;
    }

    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?float $discountPercentage): InvoiceProduct
    {
        $this->discountPercentage = $discountPercentage;
        return $this;
    }


}