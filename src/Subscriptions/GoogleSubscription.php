<?php


namespace Imdhemy\Purchases\Subscriptions;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Facades\Subscription;

class GoogleSubscription implements SubscriptionContract
{
    /**
     * @var SubscriptionPurchase
     */
    protected $subscription;

    /**
     * GoogleSubscription constructor.
     * @param SubscriptionPurchase $subscription
     */
    public function __construct(SubscriptionPurchase $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @param string $packageName
     * @param SubscriptionNotification $notification
     * @return static
     * @throws GuzzleException
     */
    public static function create(string $packageName, SubscriptionNotification $notification): self
    {
        return new self(
            Subscription::googlePlay()
                ->packageName($packageName)
                ->id($notification->getSubscriptionId())
                ->token($notification->getPurchaseToken())
                ->get()
        );
    }

    /**
     * @return Carbon
     */
    public function getExpiryTime(): Carbon
    {
        // TODO: Implement getExpiryTime() method.
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        // TODO: Implement getItemId() method.
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        // TODO: Implement getProvider() method.
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string
    {
        // TODO: Implement getUniqueIdentifier() method.
    }

    /**
     * @return mixed
     */
    public function getProviderRepresentation()
    {
        // TODO: Implement getProviderRepresentation() method.
    }
}
