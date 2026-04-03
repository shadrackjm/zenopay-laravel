<?php

namespace ShadrackMballah\ZenoPay\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use ShadrackMballah\ZenoPay\Http\ZenoPayClient;
use ShadrackMballah\ZenoPay\Responses\OrderStatusResponse;
use ShadrackMballah\ZenoPay\Responses\PaymentResponse;
use ShadrackMballah\ZenoPay\Services\MobileMoneyService;

class MobileMoneyServiceTest extends TestCase
{
    private function makeService(array $responses): MobileMoneyService
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $client = new ZenoPayClient('https://zenoapi.com/api', 'test-key');

        // Inject mock guzzle via reflection
        $ref = new \ReflectionProperty(ZenoPayClient::class, 'client');
        $ref->setAccessible(true);
        $ref->setValue($client, $guzzle);

        return new MobileMoneyService($client, 'https://example.com/webhook');
    }

    public function test_collect_returns_payment_response(): void
    {
        $service = $this->makeService([
            new Response(200, [], json_encode([
                'status'     => 'success',
                'resultcode' => '000',
                'message'    => 'Request in progress. You will receive a callback shortly',
                'order_id'   => 'order-123',
            ])),
        ]);

        $result = $service->collect('order-123', '0744963858', 5000, 'John Doe');

        $this->assertInstanceOf(PaymentResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertSame('order-123', $result->orderId);
    }

    public function test_order_status_returns_completed(): void
    {
        $service = $this->makeService([
            new Response(200, [], json_encode([
                'result' => 'SUCCESS',
                'data'   => [[
                    'order_id'       => 'order-123',
                    'payment_status' => 'COMPLETED',
                    'amount'         => 5000,
                    'reference'      => '1003020496',
                ]],
            ])),
        ]);

        $result = $service->orderStatus('order-123');

        $this->assertInstanceOf(OrderStatusResponse::class, $result);
        $this->assertTrue($result->isCompleted());
        $this->assertSame('1003020496', $result->reference);
    }
}
