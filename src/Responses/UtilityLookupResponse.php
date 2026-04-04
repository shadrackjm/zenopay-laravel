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
        // API returns "result" not "status", and data is an array of objects e.g. [{"name": "ZENO"}]
        $dataItems = $data['data'] ?? [];
        $firstItem = is_array($dataItems) && isset($dataItems[0]) ? $dataItems[0] : $dataItems;

        return new self(
            status:       $data['result'] ?? $data['status'] ?? '',
            resultCode:   $data['resultcode'] ?? $data['resultCode'] ?? '',
            customerName: $firstItem['name'] ?? $firstItem['customer_name'] ?? $data['customer_name'] ?? '',
            utilityRef:   $data['reference'] ?? $firstItem['utility_ref'] ?? $data['utility_ref'] ?? '',
            message:      $data['message'] ?? null,
            raw:          $data,
        );
    }

    public function isValid(): bool
    {
        return $this->resultCode === '000' && !empty($this->customerName);
    }
}
