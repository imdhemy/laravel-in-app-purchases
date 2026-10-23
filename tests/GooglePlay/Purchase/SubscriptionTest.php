<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\GooglePlay\Purchase;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Imdhemy\Purchases\GooglePlay\Purchase\Subscription;
use Imdhemy\Purchases\Tests\TestCase;
use LogicException;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class SubscriptionTest extends TestCase
{
    #[Test]
    public function it_retrieves_a_subscription_with_the_supplied_values(): void
    {
        $history = [];
        $client = $this->client(new Response(200, [], json_encode([
            'kind' => 'androidpublisher#subscriptionPurchaseV2',
            'regionCode' => 'US',
            'subscriptionState' => 'SUBSCRIPTION_STATE_ACTIVE',
            'acknowledgementState' => 'ACKNOWLEDGEMENT_STATE_ACKNOWLEDGED',
        ], JSON_THROW_ON_ERROR)), $history);
        $subscription = new Subscription('com.example.default')
            ->client($client)->packageName('com.example.override')->token('purchase-token');

        $purchase = $subscription->get();

        $this->assertSame('US', $purchase->regionCode);
        $this->assertCount(1, $history);
        $this->assertSame(
            'https://androidpublisher.googleapis.com/androidpublisher/v3/applications/com.example.override/purchases/subscriptionsv2/tokens/purchase-token',
            (string)$history[0]['request']->getUri(),
        );
    }

    #[Test]
    public function it_accepts_a_package_name_without_a_configured_default(): void
    {
        $failure = new RuntimeException('Request reached the supplied client.');
        $client = $this->createStub(ClientInterface::class);
        $client->method('sendRequest')->willThrowException($failure);
        $subscription = new Subscription()->client($client)->packageName('com.example.app')->token('token');

        $this->expectExceptionObject($failure);

        $subscription->get();
    }

    #[Test]
    public function it_requires_a_token_before_creating_the_default_client(): void
    {
        $subscription = new Subscription('com.example.app');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('A purchase token is required.');

        $subscription->get();
    }

    #[Test]
    public function it_requires_a_package_name_before_creating_the_default_client(): void
    {
        $subscription = new Subscription()->token('token');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('An application package name is required.');

        $subscription->get();
    }

    #[Test]
    public function it_rejects_an_empty_package_name(): void
    {
        $subscription = new Subscription('')->token('token');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('An application package name is required.');

        $subscription->get();
    }

    private function client(ResponseInterface $response, array &$history): Client
    {
        $handler = HandlerStack::create(new MockHandler([$response]));
        $handler->push(Middleware::history($history));

        return new Client(['handler' => $handler]);
    }
}
