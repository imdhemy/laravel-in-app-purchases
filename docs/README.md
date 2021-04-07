# Laravel In-App purchase

## Introduction
Google Play and App Store provide the In-App Purchase (IAP) services. IAP can be used to sell a variety of content, including subscriptions, new features, and services. The purchase event and the payment process occurs on and handled by the mobile application (iOS and Android), then your backend needs to be informed about this purchase event to deliver the purchased product or update the user's subscription state.

**Laravel In-App purchase** comes to help you to parse and validate the purchased products and handle the different states of a subscription, like New subscription , auto-renew, cancellation, expiration etc.

## Installation

Install the package via composer:
```
composer require imdhemy/laravel-purchases
```

Publish the config file:
```
php artisan vendor:publish --provider="Imdhemy\Purchases\PurchaseServiceProvider" --tag="config"
```

## Configuration
However, Laravel in-app purchase publishes a common configuration file `config/purhcase.php`, but it has different configurable keys depending on the provider, App Store Google Play.

You can check either the [App Store configuration](./app-store/configuration.md), or the [Google Play configuration](./google-play/configuration.md).
