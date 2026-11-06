# Authenticating push requests

Google Cloud Pub/Sub can attach a signed token to each push request it delivers to your notification endpoint. LIAP can verify this token, so that only your subscription can deliver Google Play notifications.

## Configuring the push subscription

Enable authentication on your Pub/Sub push subscription, select a service account, and set a custom audience. See [Google's guide to authenticating push subscriptions](https://cloud.google.com/pubsub/docs/authenticate-push-subscriptions) for the required permissions.

## Configuring your application

Enable verification and set the values you selected for the subscription:

```dotenv
GOOGLE_PLAY_PUSH_AUTHENTICATION=true
GOOGLE_PLAY_PUSH_AUDIENCE=https://example.com/liap/notifications
GOOGLE_PLAY_PUSH_SERVICE_ACCOUNT_EMAIL=push@your-project.iam.gserviceaccount.com
```

The audience must exactly match the subscription's audience. If you published `config/liap.php` you do not need to change it; these values are read through the `google_play_push_authentication` key.

Verification is disabled by default. Configure your subscription before enabling it, or deliveries will be rejected.

## Request handling

Each Google Play notification request must have an `Authorization: Bearer` token that:

- is signed by Google and has not expired,
- was issued by `accounts.google.com`,
- has the configured audience, and
- identifies the configured service account with a verified email.

A request that fails any of these checks receives a `403` response and dispatches no events. This check applies to Google Play requests only and is separate from `liap.routing.signed`. If you enable both, a request must pass both.

## Errors

LIAP downloads Google's public certificates and keeps them in your application's default cache for the lifetime given by Google's response. It uses an hour if the response does not give one.

The following failures raise an exception, which Laravel reports and turns into a `500` response by default, and Pub/Sub retries the delivery:

- Google's certificates cannot be retrieved.
- Verification is enabled without an audience or service account email. Requests are not accepted until both are set.
