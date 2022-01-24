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
use Imdhemy\Purchases\Events\AppGallery\Cancel as AppGalleryCancel;
use Imdhemy\Purchases\Events\AppGallery\Deferred as AppGalleryDeferred;
use Imdhemy\Purchases\Events\AppGallery\InitialBuy as AppGalleryInitialBuy;
use Imdhemy\Purchases\Events\AppGallery\InteractiveRenewal as AppGalleryInteractiveRenewal;
use Imdhemy\Purchases\Events\AppGallery\NewRenewalPref as AppGalleryNewRenewalPref;
use Imdhemy\Purchases\Events\AppGallery\OnHold as AppGalleryOnHold;
use Imdhemy\Purchases\Events\AppGallery\Paused as AppGalleryPaused;
use Imdhemy\Purchases\Events\AppGallery\PausePlanChanged as AppGalleryPausePlanChanged;
use Imdhemy\Purchases\Events\AppGallery\PriceChangeConfirmed as AppGalleryPriceChangeConfirmed;
use Imdhemy\Purchases\Events\AppGallery\Renewal as AppGalleryRenewal;
use Imdhemy\Purchases\Events\AppGallery\RenewalRecurring as AppGalleryRenewalRecurring;
use Imdhemy\Purchases\Events\AppGallery\RenewalRestored as AppGalleryRenewalRestored;
use Imdhemy\Purchases\Events\AppGallery\RenewalStopped as AppGalleryRenewalStopped;

return [
    'routing' => [],

    'google_play_package_name' => env('GOOGLE_PLAY_PACKAGE_NAME', 'com.example.name'),

    'appstore_password' => env('APPSTORE_PASSWORD', ''),

    'app_gallery_app_id' => env('APP_GALLERY_APP_ID', '123456789'),

    'app_gallery_app_key' => env('APP_GALLERY_APP_KEY', 'XXXYYYZZZ'),

    'app_gallery_public_key' => env('APP_GALLERY_PUBLIC_KEY', ''),

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

        /**
         * --------------------------------------------------------
         * AppGallery Events
         * --------------------------------------------------------
         */
        AppGalleryCancel::class => [],
        AppGalleryDeferred::class => [],
        AppGalleryInitialBuy::class => [],
        AppGalleryInteractiveRenewal::class => [],
        AppGalleryNewRenewalPref::class => [],
        AppGalleryOnHold::class => [],
        AppGalleryPaused::class => [],
        AppGalleryPausePlanChanged::class => [],
        AppGalleryPriceChangeConfirmed::class => [],
        AppGalleryRenewal::class => [],
        AppGalleryRenewalRecurring::class => [],
        AppGalleryRenewalRestored::class => [],
        AppGalleryRenewalStopped::class => []
    ],
];
