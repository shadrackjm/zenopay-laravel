<?php

namespace ShadrackMballah\ZenoPay;

use Illuminate\Support\ServiceProvider;
use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Services\DisbursementService;
use ShadrackMballah\ZenoPay\Services\MobileMoneyService;
use ShadrackMballah\ZenoPay\Services\SmsService;
use ShadrackMballah\ZenoPay\Services\UtilityPaymentService;

class ZenoPayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/zenopay.php', 'zenopay');

        $this->app->singleton(ZenoPayClient::class, function ($app) {
            $config = $app['config']['zenopay'];

            return new ZenoPayClient(
                baseUrl:   $config['base_url'],
                apiKey:    $config['api_key'],
                timeout:   $config['timeout'],
                verifySsl: $config['verify_ssl'],
            );
        });

        $this->app->singleton(MobileMoneyService::class, function ($app) {
            return new MobileMoneyService(
                $app->make(ZenoPayClient::class),
                $app['config']['zenopay.webhook_url'],
            );
        });

        $this->app->singleton(UtilityPaymentService::class, function ($app) {
            return new UtilityPaymentService(
                $app->make(ZenoPayClient::class),
                $app['config']['zenopay.wallet_pin'],
            );
        });

        $this->app->singleton(DisbursementService::class, function ($app) {
            return new DisbursementService(
                $app->make(ZenoPayClient::class),
                $app['config']['zenopay.wallet_pin'],
            );
        });

        $this->app->singleton(SmsService::class, function ($app) {
            $config = $app['config']['zenopay'];

            // SMS uses its own API key (ZenoPay issues separate keys per product).
            // Fall back to the main api_key if sms_api_key is not configured.
            $smsApiKey = $config['sms_api_key'] ?? $config['api_key'];

            $smsClient = new ZenoPayClient(
                baseUrl:   $config['base_url'],
                apiKey:    $smsApiKey,
                timeout:   $config['timeout'],
                verifySsl: $config['verify_ssl'],
            );

            return new SmsService($smsClient);
        });

        $this->app->singleton(ZenoPay::class, function ($app) {
            return new ZenoPay(
                $app->make(MobileMoneyService::class),
                $app->make(UtilityPaymentService::class),
                $app->make(DisbursementService::class),
                $app->make(SmsService::class),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/zenopay.php' => config_path('zenopay.php'),
            ], 'zenopay-config');
        }
    }
}
