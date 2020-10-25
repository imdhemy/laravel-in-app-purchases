<?php


namespace Imdhemy\Purchases\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Imdhemy\GooglePlay\ClientFactory;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\GooglePlay\Subscriptions\Subscription;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;

abstract class PurchaseEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var DeveloperNotification
     */
    public $developerNotification;

    /**
     * SubscriptionPurchased constructor.
     * @param DeveloperNotification $developerNotification
     */
    public function __construct(DeveloperNotification $developerNotification)
    {
        $this->developerNotification = $developerNotification;
    }

    /**
     * @return DeveloperNotification
     */
    public function getDeveloperNotification(): DeveloperNotification
    {
        return $this->developerNotification;
    }

    /**
     * @return SubscriptionNotification
     */
    public function getSubscriptionNotification(): SubscriptionNotification
    {
        return $this->developerNotification->getSubscriptionNotification();
    }

    /**
     * @return SubscriptionPurchase
     */
    public function getSubscriptionPurchase(): SubscriptionPurchase
    {
        $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $subscription = new Subscription(
            $client,
            $this->getPackageName(),
            $this->getSubscriptionId(),
            $this->getPurchaseToken()
        );

        return $subscription->get();
    }

    /**
     * @return string
     */
    public function getPackageName(): string
    {
        return $this->developerNotification->getPackageName();
    }

    /**
     * @return string
     */
    public function getSubscriptionId(): string
    {
        return $this->getSubscriptionNotification()->getSubscriptionId();
    }

    /**
     * @return string
     */
    public function getPurchaseToken(): string
    {
        return $this->getSubscriptionNotification()->getPurchaseToken();
    }
}
