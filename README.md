# ZenoPay Laravel

A Laravel 11+ package for the [ZenoPay](https://zenopay.net) payment gateway — Mobile Money, Utility Payments, Disbursements, and SMS.

## Features

| Feature | Description |
|---------|-------------|
| **Mobile Money** | USSD push to M-Pesa, Airtel, Tigo/Mixx, Halopesa |
| **Order Status** | Query payment status by order ID |
| **Utility Payments** | LUKU/electricity, DSTV, Airtime, GEPG, and more |
| **Utility Lookup** | Verify meter/card before charging |
| **Disbursements** | Send money to any mobile wallet |
| **Bulk SMS** | Single and bulk SMS with sender ID support |
| **Webhooks** | Typed, API-key-verified webhook handler |

## Requirements

- PHP 8.2+
- Laravel 11.x or 12.x

## Installation

```bash
composer require shadrack-mballah/zenopay-laravel
```

Publish the config file:

```bash
php artisan vendor:publish --tag=zenopay-config
```

Add to `.env`:

```env
ZENOPAY_API_KEY=your_api_key_here
ZENOPAY_WALLET_PIN=1234
ZENOPAY_WEBHOOK_URL=https://your-domain.com/zenopay/webhook
```

## Usage

### Mobile Money Collection

```php
use ShadrackMballah\ZenoPay\Facades\ZenoPay;

// Initiate USSD push payment
$response = ZenoPay::mobileMoney()->collect(
    orderId:    (string) \Str::uuid(),
    buyerPhone: '0744963858',
    amount:     5000,
    buyerName:  'John Doe',
);

if ($response->isSuccess()) {
    // Payment request sent — await webhook for COMPLETED status
    $orderId = $response->orderId;
}

// Check payment status
$status = ZenoPay::mobileMoney()->orderStatus($orderId);

if ($status->isCompleted()) {
    // Payment confirmed
}
```

### Webhook Handling

```php
// routes/api.php
Route::post('/zenopay/webhook', ZenoPayWebhookController::class)
    ->withoutMiddleware('auth');
```

```php
// app/Http/Controllers/ZenoPayWebhookController.php
use ShadrackMballah\ZenoPay\Webhooks\WebhookHandler;

class ZenoPayWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = WebhookHandler::parse($request);

        if ($payload->isCompleted()) {
            // Update your order as paid
            Order::where('zenopay_order_id', $payload->orderId)
                ->update(['status' => 'paid', 'payment_reference' => $payload->reference]);
        }

        return response()->json(['received' => true]);
    }
}
```

### Utility Payments

```php
use ShadrackMballah\ZenoPay\Services\UtilityPaymentService;

// Look up a LUKU meter before charging
$lookup = ZenoPay::utility()->lookupMeter('12345678', (string) \Str::uuid());

if ($lookup->isValid()) {
    // $lookup->customerName is the meter owner name

    // Pay electricity
    $result = ZenoPay::utility()->payElectricity(
        transId:     (string) \Str::uuid(),
        meterNumber: '12345678',
        amount:      10000,
        msisdn:      '0744963858',
    );
}

// Pay DSTV
ZenoPay::utility()->payDstv($transId, $smartCardNo, 50000, $msisdn);

// Buy airtime
ZenoPay::utility()->buyAirtime($transId, '0744963858', 1000);

// Pay government bill (GEPG)
ZenoPay::utility()->payGepg($transId, $controlNumber, $amount, $msisdn);

// Custom utility
ZenoPay::utility()->pay($transId, UtilityPaymentService::STARTIMES, $ref, $amount, $msisdn);
```

### Disbursements

```php
// Send money to any Tanzanian mobile wallet
$result = ZenoPay::disbursement()->send(
    transId: (string) \Str::uuid(),
    phone:   '0744963858',
    amount:  25000,
);

if ($result->isSuccess()) {
    // $result->walletBalance — your remaining balance
    // $result->amountDelivered — amount received by customer
}
```

### SMS

```php
// Single SMS
ZenoPay::sms()->send(
    recipient: '255744963858',
    message:   'Your LUKU token: 1234-5678-9012',
    senderId:  'MYAPP',
);

// Bulk SMS
ZenoPay::sms()->sendBulk(
    recipients: ['255744963858', '255712345678'],
    message:    'Your payment has been received.',
    senderId:   'MYAPP',
);

// Request a new sender ID
ZenoPay::sms()->requestSenderId('MYAPP', 'Transactional alerts for MYAPP');
```

### Supported Utility Codes

| Code | Description | Lookup |
|------|-------------|--------|
| `LUKU` | Electricity (TANESCO) | ✅ |
| `TUKUZA` | Electricity (prepaid) | ✅ |
| `TOP` | Airtime (all networks) | ❌ |
| `NCARD` | N-Card | ✅ |
| `DSTV` | DSTV Subscription | ✅ |
| `AZAMTV` | Azam TV | ✅ |
| `STARTIMES` | StarTimes | ✅ |
| `ZUKU` | Zuku TV | ✅ |
| `SMILE` | Smile Internet | ✅ |
| `ZUKUFIBER` | Zuku Fiber | ✅ |
| `TTCL` | TTCL Internet | ❌ |
| `GEPG` | Government bills | ✅ |
| `ZANMALIPO` | Zanzibar government bills | ✅ |
| `PW` | Precision Air | ✅ |
| `COASTAL` | Coastal Aviation | ✅ |
| `AURIC` | Auric Air | ✅ |

## License

MIT
