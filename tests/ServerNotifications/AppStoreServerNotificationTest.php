<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\ServerNotifications;

use DateTimeImmutable;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;
use PHPUnit\Framework\Attributes\Test;

final class AppStoreServerNotificationTest extends TestCase
{
    #[Test]
    public function it_exposes_details_from_a_legacy_notification(): void
    {
        $payload = $this->faker->legacyAppStoreNotification([
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'bid' => 'com.example.app',
            'auto_renew_product_id' => 'month_premium',
            'auto_renew_status' => 'true',
            'auto_renew_status_change_date_ms' => '1580000000123',
            'unified_receipt' => [
                'status' => 0,
                'latest_receipt_info' => [
                    $this->faker->appStoreReceipt(['product_id' => 'month_premium']),
                    $this->faker->appStoreReceipt(['product_id' => 'year_premium']),
                ],
            ],
        ]);
        $notification = new AppStoreServerNotification(ServerNotification::fromArray($payload));
        $expectedDate = new DateTimeImmutable('@1580000000.123');

        $type = $notification->getType();
        $subscription = $notification->getSubscription();
        $isTest = $notification->isTest();
        $isAutoRenewal = $notification->isAutoRenewal();
        $changedAt = $notification->getAutoRenewStatusChangedAt();
        $bundle = $notification->getBundle();
        $actualPayload = $notification->getPayload();
        $productId = $notification->getAutoRenewProductId();
        $provider = $notification->getProvider();

        $this->assertSame(ServerNotification::DID_CHANGE_RENEWAL_STATUS, $type);
        $this->assertSame('month_premium', $subscription->getItemId());
        $this->assertSame('app_store', $subscription->getProvider());
        $this->assertFalse($isTest);
        $this->assertTrue($isAutoRenewal);
        $this->assertEquals($expectedDate, $changedAt);
        $this->assertSame('com.example.app', $bundle);
        $this->assertSame($payload, $actualPayload);
        $this->assertSame('month_premium', $productId);
        $this->assertSame('app_store', $provider);
    }

    #[Test]
    public function disabled_auto_renewal(): void
    {
        $payload = $this->faker->legacyAppStoreNotification(['auto_renew_status' => 'false']);
        $notification = new AppStoreServerNotification(ServerNotification::fromArray($payload));

        $actual = $notification->isAutoRenewal();

        $this->assertFalse($actual);
    }

    #[Test]
    public function missing_change_date_returns_null(): void
    {
        $payload = $this->faker->legacyAppStoreNotification(['auto_renew_status_change_date_ms' => null]);
        $notification = new AppStoreServerNotification(ServerNotification::fromArray($payload));

        $actual = $notification->getAutoRenewStatusChangedAt();

        $this->assertNull($actual);
    }

    #[Test]
    public function get_auto_renew_status_change_date(): void
    {
        $payload = $this->faker->legacyAppStoreNotification(['auto_renew_status_change_date_ms' => '1580000000123']);
        $notification = new AppStoreServerNotification(ServerNotification::fromArray($payload));
        $expected = new Time(1580000000123);

        $actual = $notification->getAutoRenewStatusChangeDate();

        $this->assertEquals($expected, $actual);
    }
}
