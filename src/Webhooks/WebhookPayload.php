<?php

namespace ShadrackMballah\ZenoPay\Webhooks;

class WebhookPayload
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $paymentStatus,
        public readonly ?string $reference,
        public readonly array $metadata,
        public readonly array $raw,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            orderId:       $data['order_id'] ?? '',
            paymentStatus: $data['payment_status'] ?? '',
            reference:     $data['reference'] ?? null,
            metadata:      $data['metadata'] ?? [],
            raw:           $data,
        );
    }

    public function isCompleted(): bool
    {
        return strtoupper($this->paymentStatus) === 'COMPLETED';
    }

    public function isPending(): bool
    {
        return strtoupper($this->paymentStatus) === 'PENDING';
    }

    public function isFailed(): bool
    {
        return strtoupper($this->paymentStatus) === 'FAILED';
    }
}
