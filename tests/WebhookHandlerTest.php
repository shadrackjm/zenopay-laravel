<?php

namespace ShadrackMballah\ZenoPay\Tests;

use Illuminate\Http\Request;
use ShadrackMballah\ZenoPay\Exceptions\AuthenticationException;
use ShadrackMballah\ZenoPay\Webhooks\WebhookHandler;
use ShadrackMballah\ZenoPay\Webhooks\WebhookPayload;

class WebhookHandlerTest extends TestCase
{
    public function test_parses_completed_webhook(): void
    {
        $request = Request::create('/webhook', 'POST', [
            'order_id'       => 'order-123',
            'payment_status' => 'COMPLETED',
            'reference'      => '1003020496',
        ]);
        $request->headers->set('x-api-key', 'test-api-key-12345');

        $payload = WebhookHandler::parse($request);

        $this->assertInstanceOf(WebhookPayload::class, $payload);
        $this->assertTrue($payload->isCompleted());
        $this->assertSame('order-123', $payload->orderId);
    }

    public function test_throws_on_invalid_api_key(): void
    {
        $this->expectException(AuthenticationException::class);

        $request = Request::create('/webhook', 'POST', [
            'order_id'       => 'order-123',
            'payment_status' => 'COMPLETED',
        ]);
        $request->headers->set('x-api-key', 'wrong-key');

        WebhookHandler::parse($request);
    }
}
