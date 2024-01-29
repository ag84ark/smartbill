<?php

namespace Ag84ark\SmartBill\ApiResponse;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 *
 * This class represents an API response for email communication.
 * For now, it's only used when the operation is successful.
 *
 */
class EmailApiResponse implements Arrayable, Jsonable
{
    private string $message;
    private int $code;

    public function __construct(string $message, int $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public static function fromArray(array $data): EmailApiResponse
    {
        if (array_key_exists('status', $data)) {
            return new EmailApiResponse($data['status']['message'], $data['status']['code']);
        }

        return new EmailApiResponse($data['message'], $data['code']);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getStatus(): string
    {
        switch ($this->code) {
            case 0:
                return 'Success';
            case 1:
                return 'Eroare de date';
            case 2:
                return 'Eroare pe server-ul SmartBill Cloud';
            default:
                return 'Unknown';
        }

    }

    public function wasSuccessful(): bool
    {
        return $this->code === 0;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'code' => $this->code,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
