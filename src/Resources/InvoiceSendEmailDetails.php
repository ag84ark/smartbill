<?php

namespace Ag84ark\SmartBill\Resources;

class InvoiceSendEmailDetails
{
    private ?string $to = null;
    private ?string $cc = null;
    private ?string $bcc = null;

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $to): InvoiceSendEmailDetails
    {
        $this->to = $to;

        return $this;
    }

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): InvoiceSendEmailDetails
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): ?string
    {
        return $this->bcc;
    }

    public function setBcc(?string $bcc): InvoiceSendEmailDetails
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function toArray(): array
    {
        return collect([
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
        ])->filter(function ($value) {
            return ! is_null($value);
        })->toArray();
    }
}
