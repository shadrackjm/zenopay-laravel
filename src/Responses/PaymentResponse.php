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
        // ZenoPay returns '000' normally, but some operator callbacks return '200'.
        // Both indicate the request was accepted and USSD push was sent.
        return in_array($this->resultCode, ['000', '200'], true)
            || strtolower($this->status) === 'success';
    }

    public function isPending(): bool
    {
        return strtolower($this->status) === 'success' && $this->isSuccess();
    }
}
