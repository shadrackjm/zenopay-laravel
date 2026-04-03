<?php

namespace ShadrackMballah\ZenoPay\Responses;

class OrderStatusResponse
{
    public function __construct(
        public readonly string $result,
        public readonly string $orderId,
        public readonly string $paymentStatus,
        public readonly int|float $amount,
        public readonly ?string $reference,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $order = $data['data'][0] ?? $data['data'] ?? [];

        return new self(
            result:        $data['result'] ?? '',
            orderId:       $order['order_id'] ?? '',
            paymentStatus: $order['payment_status'] ?? '',
            amount:        $order['amount'] ?? 0,
            reference:     $order['reference'] ?? null,
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
