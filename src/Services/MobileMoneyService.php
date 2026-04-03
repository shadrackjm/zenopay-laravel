<?php

namespace ShadrackMballah\ZenoPay\Services;

use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Responses\OrderStatusResponse;
use ShadrackMballah\ZenoPay\Responses\PaymentResponse;

class MobileMoneyService
{
    public function __construct(protected ZenoPayClient $client, protected ?string $defaultWebhookUrl = null) {}

    /**
     * Initiate a mobile money USSD push payment.
     *
     * Supports M-Pesa (Vodacom), Airtel Money, Tigo Pesa/Mixx, and Halopesa.
     * ZenoPay automatically routes to the correct operator based on the phone number.
     *
     * @param  string       $orderId      Your unique order identifier
     * @param  string       $buyerPhone   Customer phone number (e.g. 0744963858 or 255744963858)
     * @param  int|float    $amount       Amount in TZS
     * @param  string|null  $buyerName    Customer name (optional)
     * @param  string|null  $buyerEmail   Customer email (optional)
     * @param  string|null  $webhookUrl   URL to receive payment status notifications
     * @param  array        $metadata     Optional metadata sent back in the webhook
     */
    public function collect(
        string $orderId,
        string $buyerPhone,
        int|float $amount,
        ?string $buyerName = null,
        ?string $buyerEmail = null,
        ?string $webhookUrl = null,
        array $metadata = [],
    ): PaymentResponse {
        $payload = [
            'order_id'    => $orderId,
            'buyer_phone' => $buyerPhone,
            'amount'      => $amount,
            'webhook_url' => $webhookUrl ?? $this->defaultWebhookUrl,
        ];

        if ($buyerName !== null) {
            $payload['buyer_name'] = $buyerName;
        }

        if ($buyerEmail !== null) {
            $payload['buyer_email'] = $buyerEmail;
        }

        if (!empty($metadata)) {
            $payload['metadata'] = $metadata;
        }

        $response = $this->client->post('payments/mobile_money_tanzania', $payload);

        return PaymentResponse::fromArray($response);
    }

    /**
     * Check the status of a payment order.
     *
     * @param  string  $orderId  The order ID returned from collect()
     */
    public function orderStatus(string $orderId): OrderStatusResponse
    {
        $response = $this->client->get('payments/order-status', ['order_id' => $orderId]);

        return OrderStatusResponse::fromArray($response);
    }
}
