# Laravel In-App purchase
[![Latest Version on Packagist](https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)
[![Total Downloads](https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square)](https://packagist.org/packages/imdhemy/laravel-purchases)

# Table of contents
- [Installation](#installation)
- [Configuration](#configuration)
  * [Google Application Credentials](#google-application-credentials)
  * [Routing](#routing)
  * [Event Listeners](#event-listeners)
- [Sell Products](#sell-products)
- [Sell Subscriptions](#sell-subscriptions)

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
    'routing' => [],

    'google_play_package_name' => env('GOOGLE_PLAY_PACKAGE_NAME', 'com.example.name'),

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

Each configuration option is illustrated in the [configuration section](#configuration).

# Configuration

## Google Application Credentials
Requests to the Google Play Developer API, requires authentication and scopes. To authenticate your machine create a service account, then upload the downloaded JSON file `google-app-credentials.json` to your server, and finally add `GOOGLE_APPLICATION_CREDENTIALS` key to your `.env` file and set it to the path of JSON file.

1. In the Cloud Console, go to the [Create service account](https://console.cloud.google.com/apis/credentials/serviceaccountkey?_ga=2.92610013.131807880.1603050486-1132570079.1602633482) key page.
2. From the **Service account** list, select **New service account**.
3. In the **Service account name** field, enter a name.
4. From the **Role** list, select **Project** > **Owner**.
5. Click **Create**. A JSON file that contains your key downloads to your computer.
6. Upload the JSON file to your storage directory, or any other protected directory.
6. Set the `.env` key `GOOGLE_APPLICATION_CREDENTIALS` to the JSON file path.

## Routing
This package adds a `POST` endpoint `/purchases/subscriptions/google` named `purchase.developerNotifications.google` to handle the **Real-Time Developer Notifications** pushed from Google which reflects any changes or updates of the subscription state. 

This endpoint route can be configured through the `routing` key in the config file. For example
```php
[
    // ..
   'routing' => [
        'middleware' => 'api',
        'prefix' => 'my_prefix'
    ],
    // ..
];
```

## Google Play Package Name
The package name of the application for which this subscription was purchased (for example, 'com.some.thing').

## Event Listeners
Your application should handle the different states of a subscription life. Each state update triggers a specified event. You can create an event listener to update your backend on each case.

```php
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;

class AutoRenewSubscription 
{   
    /**
    * @param SubscriptionRenewed $event
    */
    public function handle(SubscriptionRenewed $event)
    {
        // do some stuff
    }   
}
```
Add the created listener to the associated event key.

```php
    // ..
        SubscriptionRenewed::class => [AutoRenewSubscription::class],
    // ..
```

# Sell Products
 You can use the `\Imdhemy\Purchases\Facades\Product` facade to acknowledge or to get the receipt data from Google Play as follows:
 
```php
<?php
use \Imdhemy\Purchases\Facades\Product;
use \Imdhemy\GooglePlay\Products\ProductPurchase;

$itemId = 'product_id';
$token = 'purchase_token';

Product::googlePlay()->id($itemId)->token($token)->acknowledge();

/** @var ProductPurchase $productReceipt */
$productReceipt = Product::googlePlay()->id($itemId)->token($token)->get();
```

The `ProductPurchase` resource indicates the status of a user's inapp product purchase. This is its JSON Representation:

```javascript
{
  "kind": string,
  "purchaseTimeMillis": string,
  "purchaseState": integer,
  "consumptionState": integer,
  "developerPayload": string,
  "orderId": string,
  "purchaseType": integer,
  "acknowledgementState": integer,
  "purchaseToken": string,
  "productId": string,
  "quantity": integer,
  "obfuscatedExternalAccountId": string,
  "obfuscatedExternalProfileId": string,
  "regionCode": string
}
```

Each key has a getter method prefixed with `get`, for example: `getKind()` to get the `kind` value.
For more information check:
1. [Google Developer documentation](https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.products/get).
2. [PHP Google Play Billing Package](https://github.com/imdhemy/google-play-billing#get-the-consumption-state-of-a-product).
 
# Sell Subscriptions
You can use the `\Imdhemy\Purchases\Facades\Subscription` facade to acknowledge or to get the receipt data from Google Play as follows:

```php
<?php
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Facades\Subscription;

$itemId = 'product_id';
$token = 'purchase_token';

Subscription::googlePlay()->id($itemId)->token($token)->acknowledge();
// You can optionally submit a developer payload
Subscription::googlePlay()->id($itemId)->token($token)->acknowledge("your_developer_payload");

/** @var SubscriptionPurchase $subscriptionReceipt */
$subscriptionReceipt = Subscription::googlePlay()->id($itemId)->token($token)->get();
// You can optionally override the package name
Subscription::googlePlay()->packageName('com.example.name')->id($itemId)->token($token)->get();
```

The `SubscriptionPurchase` resource indicates the status of a user's inapp product purchase. This is its JSON Representation:

```javascript
{
  "kind": string,
  "startTimeMillis": string,
  "expiryTimeMillis": string,
  "autoResumeTimeMillis": string,
  "autoRenewing": boolean,
  "priceCurrencyCode": string,
  "priceAmountMicros": string,
  "introductoryPriceInfo": {
    object (IntroductoryPriceInfo)
  },
  "countryCode": string,
  "developerPayload": string,
  "paymentState": integer,
  "cancelReason": integer,
  "userCancellationTimeMillis": string,
  "cancelSurveyResult": {
    object (SubscriptionCancelSurveyResult)
  },
  "orderId": string,
  "linkedPurchaseToken": string,
  "purchaseType": integer,
  "priceChange": {
    object (SubscriptionPriceChange)
  },
  "profileName": string,
  "emailAddress": string,
  "givenName": string,
  "familyName": string,
  "profileId": string,
  "acknowledgementState": integer,
  "externalAccountId": string,
  "promotionType": integer,
  "promotionCode": string,
  "obfuscatedExternalAccountId": string,
  "obfuscatedExternalProfileId": string
}
```

Each key has a getter method prefixed with `get`, for example: `getKind()` to get the `kind` value.
For more information check:
1. [Google Developer documentation](https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.subscriptions/get).
2. [PHP Google Play Billing Package](https://github.com/imdhemy/google-play-billing#handling-the-subscription-lifecycle).

