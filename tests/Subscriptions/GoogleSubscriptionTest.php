<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Subscriptions;

use DateTimeImmutable;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Subscriptions\GoogleSubscription;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GoogleSubscriptionTest extends TestCase
{
    #[Test]
    public function it_exposes_subscription_details_from_a_google_purchase(): void
    {
        $payload = new SubscriptionPurchase(['expiryTimeMillis' => '1580000000123']);
        $subscription = new GoogleSubscription($payload, 'month_premium', 'purchase-token');
        $expectedExpiry = new DateTimeImmutable('@1580000000.123');

        $itemId = $subscription->getItemId();
        $identifier = $subscription->getUniqueIdentifier();
        $provider = $subscription->getProvider();
        $expiresAt = $subscription->getExpiresAt();
        $representation = $subscription->getProviderRepresentation();

        $this->assertSame('month_premium', $itemId);
        $this->assertSame('purchase-token', $identifier);
        $this->assertSame('google_play', $provider);
        $this->assertEquals($expectedExpiry, $expiresAt);
        $this->assertSame($payload, $representation);
    }
}
