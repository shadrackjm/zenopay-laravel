<?php

namespace ShadrackMballah\ZenoPay\Responses;

class SmsResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $resultCode,
        public readonly string $message,
        public readonly ?string $messageId,
        public readonly ?int $totalSent,
        public readonly ?int $totalRecipients,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $payload = $data['data'] ?? $data;

        return new self(
            status:           $data['status'] ?? '',
            resultCode:       $data['resultcode'] ?? $data['resultCode'] ?? '000',
            message:          $data['message'] ?? '',
            messageId:        $payload['message_id'] ?? null,
            totalSent:        $payload['sent'] ?? $payload['total_sent'] ?? null,
            totalRecipients:  $payload['total'] ?? $payload['total_recipients'] ?? null,
            raw:              $data,
        );
    }

    public function isSuccess(): bool
    {
        return strtolower($this->status) === 'success' || $this->resultCode === '000';
    }
}
