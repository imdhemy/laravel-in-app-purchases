# Laravel In-App purchase

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)
[![Total Downloads](https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)

Google Play and App Store provide the In-App Purchase (IAP) services. IAP can be used to sell a variety of content, including subscriptions, new features, and services. The purchase event and the payment process occurs on and handled by the mobile application (iOS and Android), then your backend needs to be informed about this purchase event to deliver the purchased product or update the user's subscription state.

**Laravel In-App purchase** comes to help you to parse and validate the purchased products and handle the different states of a subscription, like New subscription , auto-renew, cancellation, expiration and etc.

# Installation
Install the package via composer:

`composer require imdhemy/laravel-purchases`

Publish the config file:

`php artisan vendor:publish --provider="Imdhemy\Purchases\PurchaseServiceProvider" --tag="config"`

The published config file `config/purchase.php` looks like:

```php
return [
    'google_application_credentials' => env(
        'GOOGLE_APPLICATION_CREDENTIALS',
        storage_path('google-app-credentials.json')
    ),

    'routing' => [],

    'eventListeners' => [
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
    ],
];
```

Each configuration option is illustrated in the [configuration section](/#configuration-section).

# Configuration


