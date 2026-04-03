<?php

namespace ShadrackMballah\ZenoPay\Responses;

class PaymentResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $resultCode,
        public readonly string $message,
        public readonly string $orderId,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status:     $data['status'] ?? '',
            resultCode: $data['resultcode'] ?? $data['resultCode'] ?? '',
            message:    $data['message'] ?? '',
            orderId:    $data['order_id'] ?? '',
            raw:        $data,
        );
    }

    public function isSuccess(): bool
    {
        return $this->resultCode === '000';
    }

    public function isPending(): bool
    {
        return strtolower($this->status) === 'success' && $this->isSuccess();
    }
}
