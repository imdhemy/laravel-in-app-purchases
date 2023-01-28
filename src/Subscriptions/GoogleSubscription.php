<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Subscriptions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Exceptions\InvalidNotificationTypeException;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\ValueObjects\Time;

class GoogleSubscription implements SubscriptionContract
{
    protected SubscriptionPurchase $subscription;

    protected string $itemId;

    protected string $token;

    /**
     * GoogleSubscription constructor.
     */
    public function __construct(SubscriptionPurchase $subscription, string $itemId, string $token)
    {
        $this->subscription = $subscription;
        $this->itemId = $itemId;
        $this->token = $token;
    }

    /**
     * @throws GuzzleException
     */
    public static function createFromDeveloperNotification(
        DeveloperNotification $rtdNotification,
        ?ClientInterface $client = null
    ): self {
        $notification = $rtdNotification->getPayload();

        // Make sure the notification is a subscription notification
        if (! $notification instanceof SubscriptionNotification) {
            throw InvalidNotificationTypeException::create(SubscriptionNotification::class, get_class($notification));
        }

        $packageName = $rtdNotification->getPackageName();

        $subscriptionPurchase = Subscription::googlePlay($client)
            ->packageName($packageName)
            ->id($notification->getSubscriptionId())
            ->token($notification->getPurchaseToken())
            ->get();

        return new self(
            $subscriptionPurchase,
            $notification->getSubscriptionId(),
            $notification->getPurchaseToken()
        );
    }

    public function getExpiryTime(): Time
    {
        $time = $this->subscription->getExpiryTime();
        assert(! is_null($time));

        return Time::fromGoogleTime($time);
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getProvider(): string
    {
        return 'google_play';
    }

    public function getUniqueIdentifier(): string
    {
        return $this->token;
    }

    public function getProviderRepresentation(): SubscriptionPurchase
    {
        return $this->subscription;
    }
}
