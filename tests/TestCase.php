<?php

namespace ShadrackMballah\ZenoPay\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ShadrackMballah\ZenoPay\ZenoPayServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ZenoPayServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('zenopay.api_key', 'test-api-key-12345');
        $app['config']->set('zenopay.base_url', 'https://zenoapi.com/api');
        $app['config']->set('zenopay.wallet_pin', 1234);
        $app['config']->set('zenopay.webhook_url', 'https://example.com/webhook');
        $app['config']->set('zenopay.timeout', 30);
        $app['config']->set('zenopay.verify_ssl', true);
    }
}
