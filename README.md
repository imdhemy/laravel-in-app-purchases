# Laravel in-app purchases

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/imdhemy/laravel-in-app-purchases/run-tests?label=tests)](https://github.com/imdhemy/laravel-in-app-purchases/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)

Laravel Receipt validator for Google play Billing. After a user has made a purchase, you should do the following:
- Send the corresponding `purchaseToken` to your backend. This means that you should maintain a record of all `purchaseToken` values for all purchases.
- Verify that the `purchaseToken` value for the current purchase does not match any previous purchaseToken values. `purchaseToken` is globally unique, so you can safely use this value as a primary key in your database.
- Use the Purchases.products:get or Purchases.subscriptions:get endpoints in the Google Play Developer API to verify with Google that the purchase is legitimate.
- If the purchase is legitimate and has not been used in the past, you can then safely grant entitlement to the in-app item or subscription.

## Installation
Install the package via composer:
```
composer require imdhemy/laravel-purchases
```

Publish and run the migrations:
```
 php artisan vendor:publish --provider="Imdhemy\Purchases\PurchaseServiceProvider" --tag="migrations"
 php artisan migrate
```

Publish the config file:
```
php artisan vendor:publish --provider="Imdhemy\Purchases\PurchaseServiceProvider" --tag="config"
```

## Usage

```php
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription as GooglePlay;

$subscriptionId = "subscription_id";
$purchaseToken = "unique_purchase_token";

$receipt = GooglePlay::check($subscriptionId, $purchaseToken);

if($receipt->isValid()){
    $receipt->persist();
    // extend user's subscription
}
```
