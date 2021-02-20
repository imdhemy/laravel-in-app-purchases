<?php


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
     * @param DeveloperNotification $developerNotification
     * @param ClientInterface|null $client
     * @return static
     * @throws GuzzleException
     */
    public static function createFromDeveloperNotification(
        DeveloperNotification $developerNotification,
        ?ClientInterface $client = null
    ): self {
        $notification = $developerNotification->getSubscriptionNotification();
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

    /**
     * @return Time
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
     * @return mixed
     */
    public function getProviderRepresentation()
    {
        return $this->subscription;
    }
}
