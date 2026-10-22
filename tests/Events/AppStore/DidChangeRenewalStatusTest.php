<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events\AppStore;

use DateTimeImmutable;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;
use PHPUnit\Framework\Attributes\Test;

final class DidChangeRenewalStatusTest extends TestCase
{
    #[Test]
    public function it_exposes_renewal_details_from_a_v1_notification(): void
    {
        $payload = ServerNotification::fromArray($this->faker->legacyAppStoreNotification([
            'auto_renew_status' => 'true',
            'auto_renew_status_change_date_ms' => '1580000000123',
        ]));
        $event = new DidChangeRenewalStatus(new AppStoreServerNotification($payload));
        $expectedDate = new DateTimeImmutable('@1580000000.123');

        $isAutoRenewal = $event->isAutoRenewal();
        $changedAt = $event->getAutoRenewStatusChangedAt();

        $this->assertTrue($isAutoRenewal);
        $this->assertEquals($expectedDate, $changedAt);
    }

    #[Test]
    public function it_exposes_renewal_details_from_a_v2_notification(): void
    {
        $payload = V2DecodedPayload::fromArray([
            'notificationType' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'data' => [
                'signedRenewalInfo' => $this->sign(['autoRenewStatus' => 1])->toString(),
            ],
        ]);
        $notification = AppStoreV2ServerNotification::fromDecodedPayload($payload);
        $event = new DidChangeRenewalStatus($notification);

        $isAutoRenewal = $event->isAutoRenewal();
        $changedAt = $event->getAutoRenewStatusChangedAt();
        $legacyDate = $event->getAutoRenewStatusChangeDate();

        $this->assertTrue($isAutoRenewal);
        $this->assertNull($changedAt);
        $this->assertNull($legacyDate);
    }

    #[Test]
    public function v1_disabled_auto_renewal(): void
    {
        $payload = ServerNotification::fromArray($this->faker->legacyAppStoreNotification(['auto_renew_status' => 'false']));
        $event = new DidChangeRenewalStatus(new AppStoreServerNotification($payload));

        $actual = $event->isAutoRenewal();

        $this->assertFalse($actual);
    }

    #[Test]
    public function v2_disabled_auto_renewal(): void
    {
        $payload = V2DecodedPayload::fromArray([
            'notificationType' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'data' => [
                'signedRenewalInfo' => $this->sign(['autoRenewStatus' => 0])->toString(),
            ],
        ]);
        $notification = AppStoreV2ServerNotification::fromDecodedPayload($payload);
        $event = new DidChangeRenewalStatus($notification);

        $actual = $event->isAutoRenewal();

        $this->assertFalse($actual);
    }

    #[Test]
    public function v1_missing_change_date_returns_null(): void
    {
        $payload = ServerNotification::fromArray($this->faker->legacyAppStoreNotification(['auto_renew_status_change_date_ms' => null]));
        $event = new DidChangeRenewalStatus(new AppStoreServerNotification($payload));

        $actual = $event->getAutoRenewStatusChangedAt();

        $this->assertNull($actual);
    }

    #[Test]
    public function v1_get_auto_renew_status_change_date(): void
    {
        $payload = ServerNotification::fromArray($this->faker->legacyAppStoreNotification(['auto_renew_status_change_date_ms' => '1580000000123']));
        $event = new DidChangeRenewalStatus(new AppStoreServerNotification($payload));
        $expected = new Time(1580000000123);

        $actual = $event->getAutoRenewStatusChangeDate();

        $this->assertEquals($expected, $actual);
    }
}
