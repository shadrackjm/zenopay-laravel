<?php

namespace ShadrackMballah\ZenoPay\Services;

use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Responses\DisbursementResponse;

class DisbursementService
{
    public function __construct(protected ZenoPayClient $client, protected ?int $walletPin = null) {}

    /**
     * Send money to a mobile wallet (cash-in / disbursement).
     *
     * Supports all Tanzanian mobile money operators.
     * Reusing the same transId returns the same response (idempotent).
     *
     * @param  string     $transId   Unique transaction ID (UUID recommended)
     * @param  string     $phone     Recipient mobile number (e.g. 0744963858)
     * @param  int|float  $amount    Amount in TZS
     * @param  int|null   $pin       4-digit wallet PIN (falls back to config)
     */
    public function send(
        string $transId,
        string $phone,
        int|float $amount,
        ?int $pin = null,
    ): DisbursementResponse {
        $response = $this->client->post('payments/walletcashin/process/', [
            'transid'     => $transId,
            'utilitycode' => 'CASHIN',
            'utilityref'  => $phone,
            'amount'      => $amount,
            'pin'         => $pin ?? $this->walletPin,
        ]);

        return DisbursementResponse::fromArray($response);
    }
}
