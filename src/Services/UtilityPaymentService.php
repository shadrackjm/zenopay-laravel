<?php

namespace ShadrackMballah\ZenoPay\Services;

use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Responses\UtilityLookupResponse;
use ShadrackMballah\ZenoPay\Responses\UtilityPaymentResponse;

class UtilityPaymentService
{
    /**
     * Supported utility codes.
     */
    // Electricity
    public const LUKU         = 'LUKU';
    public const TUKUZA       = 'TUKUZA';

    // Airtime
    public const AIRTIME      = 'TOP';
    public const NCARD        = 'NCARD';

    // TV
    public const DSTV         = 'DSTV';
    public const AZAMTV       = 'AZAMTV';
    public const STARTIMES    = 'STARTIMES';
    public const ZUKU_TV      = 'ZUKU';

    // Internet
    public const SMILE        = 'SMILE';
    public const ZUKU_FIBER   = 'ZUKUFIBER';
    public const TTCL         = 'TTCL';

    // Government
    public const GEPG         = 'GEPG';
    public const ZANMALIPO    = 'ZANMALIPO';

    // Airlines
    public const PRECISION    = 'PW';
    public const COASTAL      = 'COASTAL';
    public const AURIC        = 'AURIC';

    // Other
    public const UTT          = 'UTT';
    public const SELCOMPAY    = 'SELCOMPAY';

    public function __construct(protected ZenoPayClient $client, protected ?int $walletPin = null) {}

    /**
     * Look up a customer before processing payment.
     * Supports LUKU, DSTV, AZAMTV, STARTIMES, GEPG, ZANMALIPO, airlines, and more.
     *
     * @param  string  $utilityCode  e.g. UtilityPaymentService::LUKU
     * @param  string  $utilityRef   Meter number, card number, or reference
     * @param  string  $transId      Your unique transaction ID
     */
    public function lookup(string $utilityCode, string $utilityRef, string $transId): UtilityLookupResponse
    {
        $response = $this->client->get('payments/utility/lookup/', [
            'utilitycode' => $utilityCode,
            'utilityref'  => $utilityRef,
            'transid'     => $transId,
        ]);

        return UtilityLookupResponse::fromArray($response);
    }

    /**
     * Process a utility payment.
     *
     * @param  string     $transId       Your unique transaction ID (UUID recommended)
     * @param  string     $utilityCode   e.g. UtilityPaymentService::LUKU
     * @param  string     $utilityRef    Meter number, card number, or reference
     * @param  int|float  $amount        Amount in TZS
     * @param  string     $msisdn        Customer's mobile number
     * @param  int|null   $pin           4-digit wallet PIN (falls back to config)
     */
    public function pay(
        string $transId,
        string $utilityCode,
        string $utilityRef,
        int|float $amount,
        string $msisdn,
        ?int $pin = null,
    ): UtilityPaymentResponse {
        $response = $this->client->post('payments/utilitypayment/process/', [
            'transid'     => $transId,
            'utilitycode' => $utilityCode,
            'utilityref'  => $utilityRef,
            'amount'      => $amount,
            'pin'         => $pin ?? $this->walletPin,
            'msisdn'      => $msisdn,
        ]);

        return UtilityPaymentResponse::fromArray($response);
    }

    /**
     * Shorthand: pay electricity (LUKU token).
     */
    public function payElectricity(string $transId, string $meterNumber, int|float $amount, string $msisdn, ?int $pin = null): UtilityPaymentResponse
    {
        return $this->pay($transId, self::LUKU, $meterNumber, $amount, $msisdn, $pin);
    }

    /**
     * Shorthand: look up electricity meter.
     */
    public function lookupMeter(string $meterNumber, string $transId): UtilityLookupResponse
    {
        return $this->lookup(self::LUKU, $meterNumber, $transId);
    }

    /**
     * Shorthand: buy airtime.
     */
    public function buyAirtime(string $transId, string $phoneNumber, int|float $amount, ?int $pin = null): UtilityPaymentResponse
    {
        return $this->pay($transId, self::AIRTIME, $phoneNumber, $amount, $phoneNumber, $pin);
    }

    /**
     * Shorthand: pay DSTV.
     */
    public function payDstv(string $transId, string $smartCardNumber, int|float $amount, string $msisdn, ?int $pin = null): UtilityPaymentResponse
    {
        return $this->pay($transId, self::DSTV, $smartCardNumber, $amount, $msisdn, $pin);
    }

    /**
     * Shorthand: pay government bill via GEPG.
     */
    public function payGepg(string $transId, string $controlNumber, int|float $amount, string $msisdn, ?int $pin = null): UtilityPaymentResponse
    {
        return $this->pay($transId, self::GEPG, $controlNumber, $amount, $msisdn, $pin);
    }
}
