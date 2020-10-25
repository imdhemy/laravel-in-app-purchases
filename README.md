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

Each configuration option is illustrated in the [configuration section](#configuration).

# Configuration

## Google Application Credentials
Requests to the Google Play Developer API, requires authentication and scopes. To authenticate your machine create a service account, then upload the downloaded JSON file `google-app-credentials.json` to your server, and finally set the `google_application_credentials` to the path of that file.

1. In the Cloud Console, go to the [Create service account](https://console.cloud.google.com/apis/credentials/serviceaccountkey?_ga=2.92610013.131807880.1603050486-1132570079.1602633482) key page.
2. From the **Service account** list, select **New service account**.
3. In the **Service account name** field, enter a name.
4. From the **Role** list, select **Project** > **Owner**.
5. Click **Create**. A JSON file that contains your key downloads to your computer.
6. Upload the JSON file to your storage directory, or any other protected directory.
6. Set the config key `google_application_credentials` to the JSON file path.

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
