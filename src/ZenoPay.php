<?php

namespace ShadrackMballah\ZenoPay;

use ShadrackMballah\ZenoPay\Services\DisbursementService;
use ShadrackMballah\ZenoPay\Services\MobileMoneyService;
use ShadrackMballah\ZenoPay\Services\SmsService;
use ShadrackMballah\ZenoPay\Services\UtilityPaymentService;

class ZenoPay
{
    public function __construct(
        protected MobileMoneyService $mobileMoney,
        protected UtilityPaymentService $utilityPayment,
        protected DisbursementService $disbursement,
        protected SmsService $sms,
    ) {}

    /**
     * Access Mobile Money collection and order status methods.
     *
     * @example ZenoPay::mobileMoney()->collect($orderId, $phone, $amount)
     */
    public function mobileMoney(): MobileMoneyService
    {
        return $this->mobileMoney;
    }

    /**
     * Access Utility Payment (LUKU, DSTV, Airtime, GEPG, etc.) methods.
     *
     * @example ZenoPay::utility()->payElectricity($transId, $meter, $amount, $phone)
     */
    public function utility(): UtilityPaymentService
    {
        return $this->utilityPayment;
    }

    /**
     * Access Disbursement (cash-in / send money) methods.
     *
     * @example ZenoPay::disbursement()->send($transId, $phone, $amount)
     */
    public function disbursement(): DisbursementService
    {
        return $this->disbursement;
    }

    /**
     * Access SMS (single, bulk, sender ID) methods.
     *
     * @example ZenoPay::sms()->send($phone, $message, $senderId)
     */
    public function sms(): SmsService
    {
        return $this->sms;
    }
}
