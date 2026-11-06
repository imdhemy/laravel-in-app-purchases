# One-time product notifications

Google Play sends a real-time developer notification when a one-time product purchase completes or a pending purchase is canceled. LIAP dispatches `GooglePlayNotificationReceivedEvent` for each of these notifications.

## Receiving a one-time product notification

Listen for the event and parse its message with `NotificationParser` from `imdhemy/google-play-billing`:

```php
use Illuminate\Support\Facades\Event;
use Imdhemy\GooglePlay\Domain\Rtdn\Type\OneTimeProductNotificationType;
use Imdhemy\GooglePlay\Infrastructure\Transformer\Normalizer;
use Imdhemy\GooglePlay\Interface\Rtdn\NotificationParser;
use Imdhemy\Purchases\Domain\Event\GooglePlayNotificationReceivedEvent;

Event::listen(function (GooglePlayNotificationReceivedEvent $event) {
    $notification = new NotificationParser(Normalizer::create())->parse([
        'message' => ['data' => $event->payload->message->data],
    ]);

    $oneTimeProduct = $notification->oneTimeProductNotification;

    if (OneTimeProductNotificationType::PURCHASED === $oneTimeProduct?->notificationType) {
        $productId = $oneTimeProduct->sku;
        $purchaseToken = $oneTimeProduct->purchaseToken;
    }
});
```

`parse()` returns an `Imdhemy\GooglePlay\Domain\Rtdn\Notification\DeveloperNotification`. Its `oneTimeProductNotification` property is `null` for other notification types, because the same event is dispatched for every Google Play notification. It also exposes `subscriptionNotification`, `voidedPurchaseNotification`, and `testNotification`.

`parse()` throws if the message is not valid base64-encoded JSON, because the event is also dispatched for malformed messages.

A notification alone does not establish entitlement. Verify the purchase using its token before granting access. See [Google's real-time developer notifications reference](https://developer.android.com/google/play/billing/rtdn-reference#one-time) for field meanings.

## Limitations

One-time product notifications do not dispatch a lifecycle event from `Imdhemy\Purchases\Events\GooglePlay`. Those events represent subscriptions only.
