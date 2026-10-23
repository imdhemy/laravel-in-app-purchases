# Subscription purchases

The Google Play `Subscription` facade provides access to customer subscription purchases using your application's configuration. It currently supports retrieving a purchase through `purchases.subscriptionsv2.get`.

## Configuring your application

Set your application's default package name:

```dotenv
GOOGLE_PLAY_PACKAGE_NAME=com.example.app
```

The facade reads this value from `liap.google_play_package_name` in `config/liap.php`. You may leave the default unset and supply a package name when starting an operation instead.

The default authenticated client uses Google Application Default Credentials. To use a service account key file, set its absolute path in the process environment:

```dotenv
GOOGLE_APPLICATION_CREDENTIALS=/absolute/path/to/service-account.json
```

Your credentials must have permission to access the application in Google Play Console. See [Google's API setup guide](https://developers.google.com/android-publisher/getting_started) for access requirements and [Application Default Credentials](https://cloud.google.com/docs/authentication/application-default-credentials) for other credential sources.

## Retrieving a subscription purchase

Call `get()` with a non-empty purchase token received from your application's billing flow. A non-empty package name must be configured or supplied through `packageName()`:

```php
use Imdhemy\Purchases\GooglePlay\Purchase\Facade\Subscription;

$purchase = Subscription::token($purchaseToken)->get();
```

The method returns an `Imdhemy\GooglePlay\Domain\Purchase\Entity\SubscriptionPurchase` from `imdhemy/google-play-billing`. It uses the configured package name and default authenticated client. No subscription product ID is needed.

### Inspecting the purchase

Use the returned model's public properties to read its state and line items:

```php
$state = $purchase->subscriptionState;

foreach ($purchase->lineItems as $lineItem) {
    $productId = $lineItem->productId;
}
```

Explore the returned model's typed public API using your IDE's completion or your coding agent, based on the installed `imdhemy/google-play-billing` version. See [Google's SubscriptionPurchaseV2 reference](https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.subscriptionsv2) for field meanings and restrictions.

Successful retrieval alone does not establish entitlement. Use the subscription state and relevant line items to decide access for your application.

## Selecting another application

Use `packageName()` to select an application for an operation:

```php
use Imdhemy\Purchases\GooglePlay\Purchase\Facade\Subscription;

$purchase = Subscription::packageName('com.example.other')
    ->token($purchaseToken)
    ->get();
```

This overrides the configured package name without changing credentials. The authenticated account must have access to the selected application.

## Providing a custom client

Pass an authenticated PSR-18 client to `client()`:

```php
use Imdhemy\Purchases\GooglePlay\Purchase\Facade\Subscription;

$purchase = Subscription::client($client)
    ->token($purchaseToken)
    ->get();
```

The client must implement `Psr\Http\Client\ClientInterface` and authenticate requests with the `https://www.googleapis.com/auth/androidpublisher` scope. The configured package name still applies. Default credential discovery is skipped when you supply a client.

### Using explicit credentials

The underlying package's client factory accepts service account credentials as an array:

```php
use Imdhemy\GooglePlay\Infrastructure\Http\ClientFactory;

$credentials = json_decode(
    file_get_contents('/absolute/path/to/service-account.json'),
    true,
    flags: JSON_THROW_ON_ERROR,
);

$client = ClientFactory::create($credentials);
```

Pass this client to the facade's `client()` method when starting an operation.

## Reusing operation settings

Each new facade chain starts with configured defaults. Application and client overrides apply only to that operation. If you keep an operation in a variable and reuse it, its package name, client, and token remain set until you replace them.
