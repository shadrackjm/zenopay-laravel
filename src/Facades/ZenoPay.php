<?php

namespace ShadrackMballah\ZenoPay\Facades;

use Illuminate\Support\Facades\Facade;
use ShadrackMballah\ZenoPay\Services\DisbursementService;
use ShadrackMballah\ZenoPay\Services\MobileMoneyService;
use ShadrackMballah\ZenoPay\Services\SmsService;
use ShadrackMballah\ZenoPay\Services\UtilityPaymentService;

/**
 * @method static MobileMoneyService    mobileMoney()
 * @method static UtilityPaymentService utility()
 * @method static DisbursementService   disbursement()
 * @method static SmsService            sms()
 *
 * @see \ShadrackMballah\ZenoPay\ZenoPay
 */
class ZenoPay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ShadrackMballah\ZenoPay\ZenoPay::class;
    }
}
