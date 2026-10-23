<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\GooglePlay\Purchase\Facade;

use Illuminate\Support\Facades\Facade;
use Imdhemy\GooglePlay\Domain\Purchase\Entity\SubscriptionPurchase;
use Imdhemy\Purchases\GooglePlay\Purchase\Subscription as SubscriptionService;
use Override;
use Psr\Http\Client\ClientInterface;

/**
 * @api
 *
 * @see https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.subscriptionsv2 Google Play subscription purchase API.
 *
 * @method static SubscriptionService  client(ClientInterface $client)
 * @method static SubscriptionService  packageName(string $packageName)
 * @method static SubscriptionService  token(string $token)
 * @method static SubscriptionPurchase get()
 */
final class Subscription extends Facade
{
    protected static $cached = false;

    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return SubscriptionService::class;
    }
}
