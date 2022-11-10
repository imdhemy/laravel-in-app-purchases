<?php

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

/**
 * Google Subscription
 * This class is used to represent a Google subscription when a notification
 * is received from Google play.
 */
class GoogleSubscription implements SubscriptionContract
{
    /**
     * @var SubscriptionPurchase
     */
    protected SubscriptionPurchase $subscription;

    /**
     * @var string
     */
    protected string $itemId;

    /**
     * @var string
     */
    protected string $token;

    /**
     * GoogleSubscription constructor.
     *
     * @param SubscriptionPurchase $subscription
     * @param string $itemId
     * @param string $token
     */
    public function __construct(SubscriptionPurchase $subscription, string $itemId, string $token)
    {
        $this->subscription = $subscription;
        $this->itemId = $itemId;
        $this->token = $token;
    }

    /**
     * @param DeveloperNotification $rtdNotification
     * @param ClientInterface|null $client
     *
     * @return self
     * @throws GuzzleException
     */
    public static function createFromDeveloperNotification(
        DeveloperNotification $rtdNotification,
        ?ClientInterface $client = null
    ): self {
        $notification = $rtdNotification->getPayload();

        // Make sure the notification is a subscription notification
        if (! $notification instanceof SubscriptionNotification) {
            throw InvalidNotificationTypeException::create(
                SubscriptionNotification::class,
                get_class($notification)
            );
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

    /**
     * @return Time
     * @psalm-suppress PossiblyNullArgument - We are sure expiration time is not null
     */
    public function getExpiryTime(): Time
    {
        return Time::fromGoogleTime($this->subscription->getExpiryTime());
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return 'google_play';
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string
    {
        return $this->token;
    }

    /**
     * @return SubscriptionPurchase
     */
    public function getProviderRepresentation(): SubscriptionPurchase
    {
        return $this->subscription;
    }
}
