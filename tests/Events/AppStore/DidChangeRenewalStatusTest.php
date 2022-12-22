<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events\AppStore;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Imdhemy\Purchases\Tests\TestCase;

class DidChangeRenewalStatusTest extends TestCase
{
    /**
     * @test
     */
    public function v1_is_auto_renewal(): void
    {
        $payload = ServerNotification::fromArray([
            'auto_renew_status' => 'true',
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
        ]);
        $serverNotification = new AppStoreServerNotification($payload);
        $sut = new DidChangeRenewalStatus($serverNotification);

        $actual = $sut->isAutoRenewal();

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function v2_is_auto_renewal(): void
    {
        $signedRenewalInfo = $this->sign(['autoRenewStatus' => 1])->toString();
        $decodedPayload = V2DecodedPayload::fromArray([
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'data' => [
                'signedRenewalInfo' => $signedRenewalInfo,
            ],
        ]);
        $serverNotification = AppStoreV2ServerNotification::fromDecodedPayload($decodedPayload);
        $sut = new DidChangeRenewalStatus($serverNotification);

        $actual = $sut->isAutoRenewal();

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function v1_get_auto_renew_status_change_date(): void
    {
        $payload = ServerNotification::fromArray([
            'auto_renew_status_change_date_ms' => '1580000000000',
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
        ]);
        $serverNotification = new AppStoreServerNotification($payload);
        $sut = new DidChangeRenewalStatus($serverNotification);

        $actual = $sut->getAutoRenewStatusChangeDate();

        $this->assertEquals('1580000000000', $actual->toCarbon()->getTimestampMs());
    }

    /**
     * @test
     */
    public function v2_does_not_provide_auto_renew_status_change_data(): void
    {
        $signedRenewalInfo = $this->sign([])->toString();
        $decodedPayload = V2DecodedPayload::fromArray([
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'data' => [
                'signedRenewalInfo' => $signedRenewalInfo,
            ],
        ]);
        $serverNotification = AppStoreV2ServerNotification::fromDecodedPayload($decodedPayload);
        $sut = new DidChangeRenewalStatus($serverNotification);

        $actual = $sut->getAutoRenewStatusChangeDate();

        $this->assertNull($actual);
    }
}
