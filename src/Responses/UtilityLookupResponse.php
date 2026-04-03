<?php

namespace ShadrackMballah\ZenoPay\Responses;

class UtilityLookupResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $resultCode,
        public readonly string $customerName,
        public readonly string $utilityRef,
        public readonly ?string $message,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status:       $data['status'] ?? '',
            resultCode:   $data['resultcode'] ?? $data['resultCode'] ?? '',
            customerName: $data['data']['customer_name'] ?? $data['customer_name'] ?? '',
            utilityRef:   $data['data']['utility_ref'] ?? $data['utility_ref'] ?? '',
            message:      $data['message'] ?? null,
            raw:          $data,
        );
    }

    public function isValid(): bool
    {
        return $this->resultCode === '000' && !empty($this->customerName);
    }
}
