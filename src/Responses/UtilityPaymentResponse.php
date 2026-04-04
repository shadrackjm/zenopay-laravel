<?php

namespace ShadrackMballah\ZenoPay\Responses;

class UtilityPaymentResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $resultCode,
        public readonly string $message,
        public readonly ?string $transactionId,
        public readonly ?string $zreference,
        public readonly int|float $amount,
        public readonly int|float $fee,
        public readonly ?string $token,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $payload = $data['data'] ?? $data;

        return new self(
            status:        $data['status'] ?? '',
            resultCode:    $data['resultcode'] ?? $data['resultCode'] ?? '',
            message:       $data['message'] ?? '',
            transactionId: $payload['transactionRef'] ?? $payload['transid'] ?? $payload['transaction_id'] ?? null,
            zreference:    $payload['transactionRef'] ?? $payload['zreference'] ?? null,
            amount:        $payload['amount'] ?? 0,
            fee:           $payload['fee'] ?? 0,
            token:         $payload['token'] ?? null,
            raw:           $data,
        );
    }

    public function isSuccess(): bool
    {
        return $this->resultCode === '000';
    }
}
