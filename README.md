<div align="center">
    <p><img src="cover.png" alt="Laravel In-app Purchase cover"></p>
    <p>
        <img alt="Packagist PHP Version Support" src="https://img.shields.io/packagist/php-v/imdhemy/laravel-purchases">
        <img src="https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square" alt="Latest Version on Packagist">
        <img src="https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square" alt="Total Downloads">
        <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/imdhemy/laravel-in-app-purchases">
    </p>
    <p> ✅ App Store ✅ Google Play ✅ App Gallery </p>
</div>

# Laravel In-App purchase
Google Play, App Store and App Gallery provide the In-App Purchase (IAP) services. IAP can be used to sell a variety of content, including subscriptions, new features, and services. The purchase event and the payment process occurs on and handled by the mobile application (iOS and Android), then your backend needs to be informed about this purchase event to deliver the purchased product or update the user's subscription state.

**Laravel In-App purchase** comes to help you to parse and validate the purchased products and handle the different states of a subscription, like New subscription , auto-renew, cancellation, expiration and etc.

# Table of contents
- [Installation](#installation)
- [Configuration](#configuration)
  * [i. Generic Configurations:](#i-generic-configurations)
    + [i.1 Routing](#i1-routing)
    + [i.2 Event Listeners](#i2-event-listeners)
  * [ii. Google Play Configurations:](#ii-google-play-configurations)
    + [ii.1 Google Application Credentials](#ii1-google-application-credentials)
    + [ii.2 Google Play Package Name](#ii2-google-play-package-name)
  * [iii. App Store Configurations](#iii-app-store-configurations)
    + [iii.1 App Store Sandbox](#iii1-app-store-sandbox)
    + [iii.2 App Store Password](#iii2-app-store-password)
  * [iiii. App Gallery Configurations](#iiii-app-gallery-configurations)
    + [iiii.1 App Gallery App ID](#iiii1-app-gallery-app-id)
    + [iiii.2 App Gallery App Key](#iiii2-app-gallery-app-key)
    + [iiii.3 App Gallery Public Key](#iiii3-app-gallery-public-key)
- [Sell Products](#sell-products)
  * [Google Products](#google-products)
  * [App Store Products](#app-store-products)
  * [App Gallery Products](#app-gallery-products)
- [Sell Subscriptions](#sell-subscriptions)
  * [Google Play Subscriptions](#google-play-subscriptions)
  * [App Store Subscriptions](#app-store-subscriptions)
  * [App Gallery Subscriptions](#app-gallery-subscriptions)
- [Purchase Events](#purchase-events)
- [Testing](#testing)

# Installation
Install the package via composer:

`composer require imdhemy/laravel-purchases`

Publish the config file:

`php artisan vendor:publish --provider="Imdhemy\Purchases\PurchaseServiceProvider" --tag="config"`

# Configuration

The published config file `config/purchase.php` looks like:

```php
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
        AppGalleryRenewalStopped::class => [],
    ],
];
```

Each configuration option is illustrated in the [configuration section](#configuration).

## i. Generic Configurations:
The generic configurations are not specific to a particular provider of the three supported providers (Google, Apple and Huawei).

### i.1 Routing
This package adds two `POST` endpoints to receive the [Real-Time Developer Notifications](https://developer.android.com/google/play/billing/rtdn-reference), and the [The App Store Server Notifications](https://developer.apple.com/documentation/appstoreservernotifications).

| Provider    | URI                               | Name                                  |
|-------------|-----------------------------------|---------------------------------------|
| Google Play | `/purchases/subscriptions/google` | `purchase.serverNotifications.google` |
| App Store   | `/purchases/subscriptions/apple`  | `purchase.serverNotifications.apple`  |
| App Gallery | `/purchases/subscriptions/huawei` | `purchase.serverNotifications.huawei` |

These routes can be configured through the `routing` key in the config file. For example:
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

### i.2 Event Listeners
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

All events extend the `\Imdhemy\Purchases\Events\PurchaseEvent` abstract class, which implements the `\Imdhemy\Purchases\Contracts\PurchaseEventContract` interface. Check the [Purchase Events section](#purchase-events) for more information.

## ii. Google Play Configurations:
The following set of configurations are specific to google play:

### ii.1 Google Application Credentials
Requests to the Google Play Developer API, requires authentication and scopes. To authenticate your machine create a service account, then upload the downloaded JSON file `google-app-credentials.json` to your server, and finally add `GOOGLE_APPLICATION_CREDENTIALS` key to your `.env` file and set it to the path of JSON file.

1. In the Cloud Console, go to the [Create service account](https://console.cloud.google.com/apis/credentials/serviceaccountkey?_ga=2.92610013.131807880.1603050486-1132570079.1602633482) key page.
2. From the **Service account** list, select **New service account**.
3. In the **Service account name** field, enter a name.
4. From the **Role** list, select **Project** > **Owner**.
5. Click **Create**. A JSON file that contains your key downloads to your computer.
6. Upload the JSON file to your storage directory, or any other protected directory.
6. Set the `.env` key `GOOGLE_APPLICATION_CREDENTIALS` to the JSON file path.

### ii.2 Google Play Package Name
The package name of the application for which this subscription was purchased (for example, 'com.some.thing').

## iii. App Store Configurations
The following set of configurations are specific to the App Store.

### iii.1 App Store Sandbox
The configuration key `appstore_sandbox` is a boolean value that determines whether the requests sent to the App Store are in the sandbox or not.

### iii.2 App Store Password
Request to the App Store requires the key `appstore_password` to be set. Your app’s shared secret, which is a hexadecimal string.

## iiii. App Gallery Configurations
The following set of configurations are specific to the App Gallery.

### iiii.1 App Gallery App ID
The `app_gallery_app_id` config key is your app ID, which you can get in AppGallery Connect -> Project Settings -> General information in section App information field App ID.

### iiii.2 App Gallery App Key
Request to the App Gallery requires OAuth 2.0 credentials, including the key `app_gallery_app_key`. Your app’s client secret, which is needed to get jwt.

### iiii.3 App Gallery Public Key

To verify the signature of server notifications, you need key `app_gallery_public_key`. The IAP request result will be signed by the private key of your app. This public key used to verify the signature with SHA256WithRSA or SHA256WithRSA/PSS.

# Sell Products
## Google Products
 You can use the `\Imdhemy\Purchases\Facades\Product` facade to acknowledge or to get the receipt data from Google Play as follows:
 
```php
use \Imdhemy\Purchases\Facades\Product;
use \Imdhemy\GooglePlay\Products\ProductPurchase;

$itemId = 'product_id';
$token = 'purchase_token';

Product::googlePlay()->id($itemId)->token($token)->acknowledge();
// You can optionally submit a developer payload
Product::googlePlay()->id($itemId)->token($token)->acknowledge("your_developer_payload");

/** @var ProductPurchase $productReceipt */
$productReceipt = Product::googlePlay()->id($itemId)->token($token)->get();
```
Each key has a getter method prefixed with `get`, for example: `getKind()` to get the `kind` value.
For more information check:
1. [Google Developer documentation](https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.products/get).
2. [PHP Google Play Billing Package](https://github.com/imdhemy/google-play-billing#get-the-consumption-state-of-a-product).
 
## App Store Products
You can use the `\Imdhemy\Purchases\Facades\Product` to send a [verifyReceipt](https://developer.apple.com/documentation/appstorereceipts/verifyreceipt) request to the App Store. as follows:

```php
use \Imdhemy\AppStore\Receipts\ReceiptResponse;
use \Imdhemy\Purchases\Facades\Product;

$receiptData = 'the_base64_encoded_receipt_data';
/** @var ReceiptResponse $receiptResponse */
$receiptResponse = Product::appStore()->receiptData($receiptData)->verifyReceipt();
```
As usual each key has a getter method.

For more information check:
1. [App Store Documentation](https://developer.apple.com/documentation/appstorereceipts/responsebody)
2. [PHP App Store IAP package](https://github.com/imdhemy/appstore-iap)

## App Gallery Products
You can use the `\Imdhemy\Purchases\Facades\Product` to validate purchase and get receipt data as follows:

```php
use \Huawei\IAP\Response\OrderResponse;
use \Imdhemy\Purchases\Facades\Product;

$token = 'purchase_token';
$productId = 'product_id';
/** @var OrderResponse $orderResponse */
$orderResponse = Product::appGallery()->appGalleryValidatePurchase($product_id, $token);
```
As usual each key has a getter method.

For more information check:
1. [App Gallery Documentation](https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-order-verify-purchase-token-0000001050746113)
2. [PHP AppGallery IAP package](https://github.com/CHfur/appgallery-iap)
 
# Sell Subscriptions
## Google Play Subscriptions
You can use the `\Imdhemy\Purchases\Facades\Subscription` facade to acknowledge or to get the receipt data from Google Play as follows:

```php
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

For more information check:
1. [Google Developer documentation](https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.subscriptions/get).
2. [PHP Google Play Billing Package](https://github.com/imdhemy/google-play-billing#handling-the-subscription-lifecycle).

## App Store Subscriptions
You can use the `\Imdhemy\Purchases\Facades\Subscription` to send a [verifyReceipt](https://developer.apple.com/documentation/appstorereceipts/verifyreceipt) request to the App Store. as follows:

```php
use Imdhemy\Purchases\Facades\Subscription;
// To verify a subscription receipt
$receiptData = 'the_base64_encoded_receipt_data';
$receiptResponse = Subscription::appStore()->receiptData($receiptData)->verifyReceipt();

// If the subscription is an auto-renewable one, 
//call the renewable() method before the trigger method verifyReceipt()
$receiptResponse = Subscription::appStore()->receiptData($receiptData)->renewable()->verifyReceipt();

// or you can omit the renewable() method and use the verifyRenewable() method instead
$receiptResponse = Subscription::appStore()->receiptData($receiptData)->verifyRenewable();
```

For more information check:
1. [Validating Receipts with the App Store](https://developer.apple.com/documentation/storekit/in-app_purchase/validating_receipts_with_the_app_store)
2. [PHP App Store IAP package](https://github.com/imdhemy/appstore-iap)

## App Gallery Subscriptions
You can use the `\Imdhemy\Purchases\Facades\Subscription` to validate subscription purchase token and get receipt data. as follows:

```php
use Imdhemy\Purchases\Facades\Subscription;
use \Huawei\IAP\Response\SubscriptionResponse;

$token = 'purchase_token';
$productId = 'product_id';
$subscriptionId = 'subscription_id';

/** @var SubscriptionResponse $subscriptionResponse */
$subscriptionResponse = Subscription::appGallery()
                              ->appGalleryValidateSubscription($subscriptionId, $productId, $token);
```

For more information check:
1. [API for Verifying the Purchase Token for the Subscription Service](https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-subscription-verify-purchase-token-0000001050706080)
2. [PHP AppGallery IAP package](https://github.com/CHfur/appgallery-iap)

# Purchase Events
As mentioned the [configuration section](#i2-event-listeners), Your application should handle the different states of a subscription life. Each state update triggers a specified event. You can create an event listener to update your backend on each case.

All triggered events implement `Imdhemy\Purchases\Contracts\PurchaseEventContract` interface, which allows you to get a standard representation of the received notification through the `getServerNotification()` method.

The retrieved notification is of type `\Imdhemy\Purchases\Contracts\ServerNotificationContract` which allows you to get a standard representation of the subscription using the `getSubscription()` method.

The subscription object provides the following methods:
1. `getExpiryTime()` returns a `Time` object that tells the expiration time of the subscription.
2. `getItemId()` returns the purchased subscription id.
3. `getProvider()` returns the provider of this subscription, either `google_play` or `app_store`.
4. `getUniqueIdentifier()` returns a unique identifier for this subscription.
5. `getProviderRepresentation()` returns either `SubscriptionPurchase` or `ReceiptResponse` based on the provider.

Here is an example of an auto-renewed subscription:

```php
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;

class AutoRenewSubscription 
{   
    /**
    * @param SubscriptionRenewed $event
    */
    public function handle(SubscriptionRenewed $event)
    {   
       // The following data can be retrieved from the event
       $notification = $event->getServerNotification();
       $subscription = $notification->getSubscription();
       $uniqueIdentifier = $subscription->getUniqueIdentifier();
       $expirationTime = $subscription->getExpiryTime();
        
       // The following steps are up to you, this is only an imagined scenario.
       $user = $this->findUserBySubscriptionId($uniqueIdentifier);
       $user->getSubscription()->setExpirationTime($expirationTime);
       $user->save();        
        
       $this->notifyUserAboutUpdate($user);
    }   
}
```

# Testing
TODO: add testing examples.
