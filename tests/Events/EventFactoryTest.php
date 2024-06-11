<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Events;

use Imdhemy\AppStore\ServerNotifications\ServerNotification as AppstoreNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification as GoogleNotification;
use Imdhemy\Purchases\Contracts\ServerNotificationContract as ServerNotification;
use Imdhemy\Purchases\Events\AppStore\Cancel;
use Imdhemy\Purchases\Events\AppStore\ConsumptionRequest;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalPref;
use Imdhemy\Purchases\Events\AppStore\DidChangeRenewalStatus;
use Imdhemy\Purchases\Events\AppStore\DidFailToRenew;
use Imdhemy\Purchases\Events\AppStore\DidRecover;
use Imdhemy\Purchases\Events\AppStore\DidRenew;
use Imdhemy\Purchases\Events\AppStore\InitialBuy;
use Imdhemy\Purchases\Events\AppStore\InteractiveRenewal;
use Imdhemy\Purchases\Events\AppStore\PriceIncreaseConsent;
use Imdhemy\Purchases\Events\AppStore\Refund;
use Imdhemy\Purchases\Events\AppStore\Revoke;
use Imdhemy\Purchases\Events\EventFactory;
use Imdhemy\Purchases\Events\FallbackEvent;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionCanceled;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionDeferred;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionExpired;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionInGracePeriod;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionOnHold;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPaused;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPauseScheduleChanged;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPriceChangeConfirmed;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRestarted;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRevoked;
use Imdhemy\Purchases\Tests\TestCase;

class EventFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider googlePlayEventsProvider
     * @dataProvider appStoreEventsProvider
     */
    public function create(string $provider, string $type, string $expectedEvent): void
    {
        $serverNotification = $this->createMock(ServerNotification::class);
        $serverNotification->method('getProvider')->willReturn($provider);
        $serverNotification->method('getType')->willReturn($type);

        $event = (new EventFactory())->create($serverNotification);

        $this->assertSame($expectedEvent, get_class($event));
    }

    public function googlePlayEventsProvider(): array
    {
        $data = [
            [
                GoogleNotification::SUBSCRIPTION_RECOVERED,
                SubscriptionRecovered::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_RENEWED,
                SubscriptionRenewed::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_CANCELED,
                SubscriptionCanceled::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_PURCHASED,
                SubscriptionPurchased::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_ON_HOLD,
                SubscriptionOnHold::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_IN_GRACE_PERIOD,
                SubscriptionInGracePeriod::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_RESTARTED,
                SubscriptionRestarted::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_PRICE_CHANGE_CONFIRMED,
                SubscriptionPriceChangeConfirmed::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_DEFERRED,
                SubscriptionDeferred::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_PAUSED,
                SubscriptionPaused::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_PAUSE_SCHEDULE_CHANGED,
                SubscriptionPauseScheduleChanged::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_REVOKED,
                SubscriptionRevoked::class,
            ],
            [
                GoogleNotification::SUBSCRIPTION_EXPIRED,
                SubscriptionExpired::class,
            ],
            [
                'InvalidType',
                FallbackEvent::class,
            ],
        ];

        foreach ($data as &$item) {
            $item[0] = (string)$item[0];
            array_unshift($item, ServerNotification::PROVIDER_GOOGLE_PLAY);
        }

        return $data;
    }

    public function appStoreEventsProvider(): array
    {
        $data = [
            [
                AppstoreNotification::CANCEL,
                Cancel::class,
            ],
            [
                AppstoreNotification::CONSUMPTION_REQUEST,
                ConsumptionRequest::class,
            ],
            [
                AppstoreNotification::DID_CHANGE_RENEWAL_PREF,
                DidChangeRenewalPref::class,
            ],
            [
                AppstoreNotification::DID_CHANGE_RENEWAL_STATUS,
                DidChangeRenewalStatus::class,
            ],
            [
                AppstoreNotification::DID_FAIL_TO_RENEW,
                DidFailToRenew::class,
            ],
            [
                AppstoreNotification::DID_RECOVER,
                DidRecover::class,
            ],
            [
                AppstoreNotification::DID_RENEW,
                DidRenew::class,
            ],
            [
                AppstoreNotification::INITIAL_BUY,
                InitialBuy::class,
            ],
            [
                AppstoreNotification::INTERACTIVE_RENEWAL,
                InteractiveRenewal::class,
            ],
            [
                AppstoreNotification::PRICE_INCREASE_CONSENT,
                PriceIncreaseConsent::class,
            ],
            [
                AppstoreNotification::REFUND,
                Refund::class,
            ],
            [
                AppstoreNotification::REVOKE,
                Revoke::class,
            ],
            [
                'InvalidType',
                FallbackEvent::class,
            ],
        ];

        foreach ($data as &$item) {
            array_unshift($item, ServerNotification::PROVIDER_APP_STORE);
        }

        return $data;
    }
}
