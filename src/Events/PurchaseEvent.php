<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Events;

use GuzzleHttp\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

abstract class PurchaseEvent implements PurchaseEventContract
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var ServerNotificationContract|AppStoreServerNotification|GoogleServerNotification
     */
    protected $serverNotification;

    /**
     * SubscriptionPurchased constructor.
     */
    public function __construct(ServerNotificationContract $serverNotification)
    {
        $this->serverNotification = $serverNotification;
    }

    public function getServerNotification(): ServerNotificationContract
    {
        return $this->serverNotification;
    }

    public function getSubscription(?Client $client = null): SubscriptionContract
    {
        return $this->serverNotification->getSubscription($client);
    }

    public function getSubscriptionId(): string
    {
        return $this->getSubscription()->getItemId();
    }

    public function getSubscriptionIdentifier(): string
    {
        return $this->getSubscription()->getUniqueIdentifier();
    }
}
