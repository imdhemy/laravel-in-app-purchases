<?php

use Imdhemy\Purchases\Events\AppStore\Cancel;
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
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionCanceled;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionDeferred;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionExpired;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionInGracePeriod;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionOnHold;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionPaused;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionPauseScheduleChanged;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionPriceChangeConfirmed;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionRecovered;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionRenewed;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionRestarted;
use Simpleclick\Purchases\Events\GooglePlay\SubscriptionRevoked;

return [
    'routing' => [],

    'google_play_package_name' => env('GOOGLE_PLAY_PACKAGE_NAME', 'com.example.name'),

    'appstore_password' => env('APPSTORE_PASSWORD', ''),

    'eventListeners' => [
        /**
         * --------------------------------------------------------
         * Google Play Events
         * --------------------------------------------------------
         */
        SubscriptionPurchased::class => [],
        SubscriptionRenewed::class => [],
        SubscriptionInGracePeriod::class => [],
        SubscriptionExpired::class => [],
        SubscriptionCanceled::class => [],
        SubscriptionPaused::class => [],
        SubscriptionRestarted::class => [],
        SubscriptionDeferred::class => [],
        SubscriptionRevoked::class => [],
        SubscriptionOnHold::class => [],
        SubscriptionRecovered::class => [],
        SubscriptionPauseScheduleChanged::class => [],
        SubscriptionPriceChangeConfirmed::class => [],

        /**
         * --------------------------------------------------------
         * Appstore Events
         * --------------------------------------------------------
         */
        Cancel::class => [],
        DidChangeRenewalPref::class => [],
        DidChangeRenewalStatus::class => [],
        DidFailToRenew::class => [],
        DidRecover::class => [],
        DidRenew::class => [],
        InitialBuy::class => [],
        InteractiveRenewal::class => [],
        PriceIncreaseConsent::class => [],
        Refund::class => [],
        Revoke::class => [],
    ],
];
