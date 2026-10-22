<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Subscriptions;

use DateTimeImmutable;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;
use PHPUnit\Framework\Attributes\Test;

final class AppStoreSubscriptionTest extends TestCase
{
    #[Test]
    public function it_exposes_subscription_details_from_a_receipt(): void
    {
        $payload = LatestReceiptInfo::fromArray($this->faker->appStoreReceipt([
            'product_id' => 'month_premium',
            'original_transaction_id' => '1000000123456789',
            'expires_date_ms' => '1580000000123',
        ]));
        $subscription = new AppStoreSubscription($payload);
        $expectedExpiry = new DateTimeImmutable('@1580000000.123');
        $expectedLegacyExpiry = new Time(1580000000123);

        $itemId = $subscription->getItemId();
        $identifier = $subscription->getUniqueIdentifier();
        $provider = $subscription->getProvider();
        $expiresAt = $subscription->getExpiresAt();
        $expiryTime = $subscription->getExpiryTime();
        $representation = $subscription->getProviderRepresentation();

        $this->assertSame('month_premium', $itemId);
        $this->assertSame('1000000123456789', $identifier);
        $this->assertSame('app_store', $provider);
        $this->assertEquals($expectedExpiry, $expiresAt);
        $this->assertEquals($expectedLegacyExpiry, $expiryTime);
        $this->assertSame($payload, $representation);
    }
}
