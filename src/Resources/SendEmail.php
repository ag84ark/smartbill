<?php

namespace Ag84ark\SmartBill\Resources;

use Ag84ark\SmartBill\Enums\DocumentTypeEnum;
use Ag84ark\SmartBill\Exceptions\InvalidDocumentTypeException;

class SendEmail
{
    private string $companyVatCode;

    /**
     * seria documentului pe care il trimiteti pe email (trebuie sa fie definita in SmartBill)
     */
    private string $seriesName;

    /**
     * numarul documentului
     */
    private string $number;

    /**
     * tipul documentului
     * valori posibile: “factura” sau “proforma”
     */
    private string $type;

    /**
     * subiectul email-ului
     * daca nu se specifica, se poate prelua din ce este configurat in SmartBill
     */
    private ?string $subject = null;

    /**
     * continutul email-ului
     * daca nu se specifica, se poate prelua din ce este configurat in contul Cloud
     */
    private ?string $bodyText = null;

    /**
     * adresa de e-mail a destinatarului
     * daca nu se specifica, se va lua emailul specificat pentru clientul caruia i s-a emis documentul
     */
    private ?string $to = null;

    private ?string $cc = null;

    private ?string $bcc = null;

    public function __construct(string $seriesName, string $number, string $type = 'factura')
    {
        $this->setSeriesName($seriesName);
        $this->setNumber($number);
        $this->setType($type);
        $this->companyVatCode = config('smartbill.vatCode');
    }

    public function toArray(): array
    {
        $data = [
            'companyVatCode' => $this->getCompanyVatCode(),
            'seriesName' => $this->getSeriesName(),
            'number' => $this->getNumber(),
            'type' => $this->getType(),
        ];

        if (! empty($this->getSubject())) {
            $data['subject'] = base64_encode($this->getSubject());
        }
        if (! empty($this->getBodyText())) {
            $data['bodyText'] = base64_encode($this->getBodyText());
        }
        if (! empty($this->getTo())) {
            $data['to'] = $this->getTo();
        }
        if (! empty($this->getCc())) {
            $data['cc'] = $this->getCc();
        }
        if (! empty($this->getBcc())) {
            $data['bcc'] = $this->getBcc();
        }

        return $data;
    }

    public function getCompanyVatCode(): string
    {
        return $this->companyVatCode;
    }

    public function setCompanyVatCode(string $companyVatCode): SendEmail
    {
        $this->companyVatCode = $companyVatCode;

        return $this;
    }

    public function getSeriesName(): string
    {
        return $this->seriesName;
    }

    public function setSeriesName(string $seriesName): SendEmail
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): SendEmail
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): SendEmail
    {
        if (! DocumentTypeEnum::isValidForEmail($type)) {
            throw new InvalidDocumentTypeException($type);
        }
        $this->type = $type;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): SendEmail
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBodyText(): ?string
    {
        return $this->bodyText;
    }

    public function setBodyText(?string $bodyText): SendEmail
    {
        $this->bodyText = $bodyText;

        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $to): SendEmail
    {
        $this->to = $to;

        return $this;
    }

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): SendEmail
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): ?string
    {
        return $this->bcc;
    }

    public function setBcc(?string $bcc): SendEmail
    {
        $this->bcc = $bcc;

        return $this;
    }
}
