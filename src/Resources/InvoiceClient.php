<?php

namespace Ag84ark\SmartBill\Resources;

class InvoiceClient
{
    /** Nume client (caracterele speciale nu sunt permise ) */
    private string $name;

    /** CIF-ul clientului */
    private ?string $vatCode = null;

    /** cod client */
    private ?string $code = null;

    /**
     * true sau 1 - client platitor tva
     * false sau 0 - client neplatitor tva
     */
    private bool $isTaxPayer = false;

    /** numar registrul comerţului */
    private ?string $regCom = null;

    /** adresa clientului */
    private ?string $address = null;

    /** persoana de contact client */
    private ?string $contact = null;

    /** orasul clientului */
    private ?string $city = null;

    /** judetul clientului */
    private ?string $county = null;

    /** tara clientului */
    private ?string $country = null;

    /** email client */
    private ?string $email = null;

    /** telefon client */
    private ?string $phone = null;

    /** cont bancar client */
    private ?string $iban = null;

    /** banca client */
    private ?string $bank = null;

    /** salvare client in baza de date */
    private bool $saveToDb = false;


    /** Returns array without null values */
    public function toArray(): array
    {
        return collect([
            'name' => $this->name,
            'vatCode' => $this->vatCode,
            'isTaxPayer' => $this->isTaxPayer,
            'regCom' => $this->regCom,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'county' => $this->county,
            'email' => $this->email,
            'saveToDb' => $this->saveToDb,
            'iban' => $this->iban,
            'bank' => $this->bank,
            'phone' => $this->phone,
            'contact' => $this->contact,
            'code' => $this->code,
        ])->filter(function ($value) {
            return !is_null($value);
        })->toArray();
    }

    public static function fromArray($data): self
    {
        $invoice = new self();
        $invoice->setName($data['name'] ?? '')
            ->setVatCode($data['vatCode'] ?? null)
            ->setIsTaxPayer($data['isTaxPayer'] ?? false)
            ->setRegCom($data['regCom'] ?? null)
            ->setAddress($data['address'] ?? null)
            ->setCity($data['city'] ?? '')
            ->setCountry($data['country'] ?? null)
            ->setCounty($data['county'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setSaveToDb($data['saveToDb'] ?? false)
            ->setIban($data['iban'] ?? null)
            ->setBank($data['bank'] ?? null)
            ->setPhone($data['phone'] ?? null)
            ->setContact($data['contact'] ?? null)
            ->setCode($data['code'] ?? null);

        return $invoice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): InvoiceClient
    {
        $this->name = $name;
        return $this;
    }

    public function getVatCode(): ?string
    {
        return $this->vatCode;
    }

    public function setVatCode(?string $vatCode): InvoiceClient
    {
        $this->vatCode = $vatCode;
        return $this;
    }

    public function isTaxPayer(): bool
    {
        return $this->isTaxPayer;
    }

    public function setIsTaxPayer(bool $isTaxPayer): InvoiceClient
    {
        $this->isTaxPayer = $isTaxPayer;
        return $this;
    }

    public function getRegCom(): ?string
    {
        return $this->regCom;
    }

    public function setRegCom(?string $regCom): InvoiceClient
    {
        $this->regCom = $regCom;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(?string $address): InvoiceClient
    {
        $this->address = $address;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(?string $city): InvoiceClient
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(?string $country): InvoiceClient
    {
        $this->country = $country;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): InvoiceClient
    {
        $this->email = $email;
        return $this;
    }

    public function isSaveToDb(): bool
    {
        return $this->saveToDb;
    }

    public function setSaveToDb(bool $saveToDb): InvoiceClient
    {
        $this->saveToDb = $saveToDb;
        return $this;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): InvoiceClient
    {
        $this->code = $code;
        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): InvoiceClient
    {
        $this->contact = $contact;
        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(?string $county): InvoiceClient
    {
        $this->county = $county;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): InvoiceClient
    {
        $this->phone = $phone;
        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): InvoiceClient
    {
        $this->iban = $iban;
        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): InvoiceClient
    {
        $this->bank = $bank;
        return $this;
    }
}