<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Subscriptions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Facades\Subscription;
use Imdhemy\Purchases\ValueObjects\Time;

class GoogleSubscription implements SubscriptionContract
{
    /**
     * @var SubscriptionPurchase
     */
    protected $subscription;

    /**
     * @var string
     */
    protected $itemId;

    /**
     * @var string
     */
    protected $token;

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
     * @return static
     *
     * @throws GuzzleException
     */
    public static function createFromDeveloperNotification(
        DeveloperNotification $developerNotification,
        ?ClientInterface $client = null
    ): self {
        $notification = $developerNotification->getPayload();
        $packageName = $developerNotification->getPackageName();

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
        return Time::fromGoogleTime($this->subscription->getExpiryTime());
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
