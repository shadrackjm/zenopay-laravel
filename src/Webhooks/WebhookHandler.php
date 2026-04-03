<?php

namespace ShadrackMballah\ZenoPay\Webhooks;

use Illuminate\Http\Request;
use ShadrackMballah\ZenoPay\Exceptions\AuthenticationException;

class WebhookHandler
{
    /**
     * Parse and validate an incoming ZenoPay webhook request.
     *
     * ZenoPay sends the same x-api-key header in webhook callbacks.
     * Call this in your webhook controller to get a typed payload.
     *
     * @throws AuthenticationException  If the API key header doesn't match
     */
    public static function parse(Request $request, bool $verifyApiKey = true): WebhookPayload
    {
        if ($verifyApiKey) {
            $expectedKey = config('zenopay.api_key');
            $receivedKey = $request->header('x-api-key');

            if ($expectedKey && $receivedKey !== $expectedKey) {
                throw new AuthenticationException('Webhook API key mismatch.');
            }
        }

        return WebhookPayload::fromArray($request->all());
    }
}
