<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ZenoPay API Key
    |--------------------------------------------------------------------------
    | Your ZenoPay API key from the developer dashboard at zenopay.net
    */
    'api_key' => env('ZENOPAY_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | ZenoPay API Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('ZENOPAY_BASE_URL', 'https://zenoapi.com/api'),

    /*
    |--------------------------------------------------------------------------
    | Wallet PIN
    |--------------------------------------------------------------------------
    | Required for Utility Payments and Disbursements.
    | Your 4-digit ZenoPay wallet PIN.
    */
    'wallet_pin' => env('ZENOPAY_WALLET_PIN'),

    /*
    |--------------------------------------------------------------------------
    | Default Webhook URL
    |--------------------------------------------------------------------------
    | Fallback webhook URL if not specified per request.
    */
    'webhook_url' => env('ZENOPAY_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('ZENOPAY_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    */
    'verify_ssl' => env('ZENOPAY_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | SMS API Key
    |--------------------------------------------------------------------------
    | ZenoPay issues a separate API key for the Bulk SMS service.
    | If not set, falls back to the main api_key.
    */
    'sms_api_key' => env('ZENOPAY_SMS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | SMS Sender ID
    |--------------------------------------------------------------------------
    | The sender ID displayed on outgoing SMS messages.
    | Must be pre-registered with ZenoPay (max 11 characters).
    */
    'sms_sender_id' => env('ZENOPAY_SMS_SENDER_ID', 'MED'),
];
