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
        // ZenoPay wraps the Selcom response under 'selcom_response' for utility payments.
        // The resultcode and token details live there, not at the top level.
        $selcom  = $data['selcom_response'] ?? [];
        $payload = $data['data'] ?? $data;

        return new self(
            status:        $data['status'] ?? $selcom['result'] ?? '',
            resultCode:    $selcom['resultcode'] ?? $data['resultcode'] ?? $data['resultCode'] ?? '',
            message:       $selcom['message'] ?? $data['message'] ?? '',
            transactionId: $selcom['transid'] ?? $payload['transactionRef'] ?? $payload['transid'] ?? $payload['transaction_id'] ?? null,
            zreference:    $selcom['reference'] ?? $payload['transactionRef'] ?? $payload['zreference'] ?? null,
            amount:        $payload['amount'] ?? 0,
            fee:           $payload['fee'] ?? 0,
            token:         $selcom['token'] ?? $payload['token'] ?? null,
            raw:           $data,
        );
    }

    public function isSuccess(): bool
    {
        return $this->resultCode === '000' || strtolower($this->status) === 'success';
    }
}
