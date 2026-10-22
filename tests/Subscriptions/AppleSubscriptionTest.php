<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Subscriptions;

use DateTimeImmutable;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Subscriptions\AppleSubscription;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class AppleSubscriptionTest extends TestCase
{
    #[Test]
    public function it_exposes_subscription_details_from_a_v2_payload(): void
    {
        $payload = V2DecodedPayload::fromArray([
            'data' => [
                'signedTransactionInfo' => $this->sign([
                    'productId' => 'month_premium',
                    'originalTransactionId' => '1000000123456789',
                    'expiresDate' => 1580000000123,
                ])->toString(),
            ],
        ]);
        $subscription = AppleSubscription::fromV2DecodedPayload($payload);
        $expectedExpiry = new DateTimeImmutable('@1580000000.123');

        $itemId = $subscription->getItemId();
        $identifier = $subscription->getUniqueIdentifier();
        $provider = $subscription->getProvider();
        $expiresAt = $subscription->getExpiresAt();
        $representation = $subscription->getProviderRepresentation();

        $this->assertSame('month_premium', $itemId);
        $this->assertSame('1000000123456789', $identifier);
        $this->assertSame('app_store', $provider);
        $this->assertEquals($expectedExpiry, $expiresAt);
        $this->assertSame($payload, $representation);
    }
}
