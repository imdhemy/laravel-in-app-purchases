<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events\AppStore;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalPref;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Imdhemy\Purchases\Tests\TestCase;

class DidChangeRenewalPrefTest extends TestCase
{
    /**
     * @test
     */
    public function v1_get_auto_renew_product_id(): void
    {
        $payload = ServerNotification::fromArray([
            'auto_renew_product_id' => 'com.example.product',
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_PREF,
        ]);
        $serverNotification = new AppStoreServerNotification($payload);
        $sut = new DidChangeRenewalPref($serverNotification);

        $actual = $sut->getAutoRenewProductId();

        $this->assertEquals('com.example.product', $actual);
    }

    /**
     * @test
     */
    public function v2_get_aut_renew_product_id(): void
    {
        $signedRenewalInfo = $this->sign([
            'autoRenewProductId' => 'com.example.product',
        ])->toString();

        $decodedPayload = V2DecodedPayload::fromArray([
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_PREF,
            'data' => [
                'signedRenewalInfo' => $signedRenewalInfo,
            ],
        ]);
        $serverNotification = AppStoreV2ServerNotification::fromDecodedPayload($decodedPayload);
        $sut = new DidChangeRenewalPref($serverNotification);

        $actual = $sut->getAutoRenewProductId();

        $this->assertEquals('com.example.product', $actual);
    }
}
