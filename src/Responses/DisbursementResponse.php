<?php

namespace ShadrackMballah\ZenoPay\Responses;

class DisbursementResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $resultCode,
        public readonly string $message,
        public readonly ?string $transactionId,
        public readonly ?string $zreference,
        public readonly int|float $amount,
        public readonly int|float $fee,
        public readonly int|float $amountDelivered,
        public readonly int|float $walletBalance,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $payload = $data['data'] ?? $data;

        return new self(
            status:          $data['status'] ?? '',
            resultCode:      $data['resultcode'] ?? $data['resultCode'] ?? '',
            message:         $data['message'] ?? '',
            transactionId:   $payload['transid'] ?? null,
            zreference:      $payload['zreference'] ?? null,
            amount:          $payload['amount'] ?? 0,
            fee:             $payload['fee'] ?? 0,
            amountDelivered: $payload['amount_delivered'] ?? $payload['amount'] ?? 0,
            walletBalance:   $payload['wallet_balance'] ?? 0,
            raw:             $data,
        );
    }

    public function isSuccess(): bool
    {
        return $this->resultCode === '000';
    }
}
