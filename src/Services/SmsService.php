<?php

namespace ShadrackMballah\ZenoPay\Services;

use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Responses\SmsResponse;

class SmsService
{
    public const TYPE_ALERT     = 'alert';
    public const TYPE_MARKETING = 'marketing';

    public function __construct(protected ZenoPayClient $client) {}

    /**
     * Send a single SMS.
     *
     * @param  string  $recipient   Phone number in E.164 format (e.g. 2557XXXXXXXX)
     * @param  string  $message     SMS content
     * @param  string  $senderId    Approved sender ID (max 11 chars, no spaces)
     * @param  string  $type        'alert' or 'marketing'
     */
    public function send(
        string $recipient,
        string $message,
        string $senderId,
        string $type = self::TYPE_ALERT,
    ): SmsResponse {
        $response = $this->client->post('sms/v1/send/', [
            'contacts'     => $recipient,
            'message'      => $message,
            'sender_id'    => $senderId,
            'message_type' => $type,
        ]);

        return SmsResponse::fromArray($response);
    }

    /**
     * Send bulk SMS to multiple recipients.
     *
     * @param  string[]  $recipients  Array of phone numbers in E.164 format
     * @param  string    $message     SMS content
     * @param  string    $senderId    Approved sender ID
     * @param  string    $type        'alert' or 'marketing'
     */
    public function sendBulk(
        array $recipients,
        string $message,
        string $senderId,
        string $type = self::TYPE_ALERT,
    ): SmsResponse {
        $response = $this->client->post('sms/v1/send/', [
            'contacts'     => $recipients,
            'message'      => $message,
            'sender_id'    => $senderId,
            'message_type' => $type,
        ]);

        return SmsResponse::fromArray($response);
    }

    /**
     * Request a new Sender ID (requires approval from ZenoPay).
     *
     * @param  string  $senderId     Desired sender ID (max 11 chars, no spaces)
     * @param  string  $description  Purpose of this sender ID
     */
    public function requestSenderId(string $senderId, string $description): array
    {
        return $this->client->post('sms/sender-ids/', [
            'sender_id'   => $senderId,
            'description' => $description,
        ]);
    }

    /**
     * List all registered Sender IDs and their approval status.
     */
    public function senderIds(): array
    {
        return $this->client->get('sms/sender-ids/');
    }
}
