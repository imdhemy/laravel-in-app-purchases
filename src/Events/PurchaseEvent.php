<?php


namespace Imdhemy\Purchases\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Imdhemy\Purchases\Contracts\PurchaseEventContract;
use Imdhemy\Purchases\Contracts\ServerNotificationContract;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ServerNotifications\AppGalleryServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

abstract class PurchaseEvent implements PurchaseEventContract
{
    use Dispatchable, SerializesModels;

    /**
     * @var ServerNotificationContract|AppStoreServerNotification|GoogleServerNotification|AppGalleryServerNotification
     */
    protected $serverNotification;

    protected $rawData;

    /**
     * SubscriptionPurchased constructor.
     * @param  ServerNotificationContract  $serverNotification
     */
    public function __construct(ServerNotificationContract $serverNotification, string $rawData)
    {
        $this->serverNotification = $serverNotification;
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getRawData(): string
    {
        return $this->rawData;
    }

    /**
     * @return ServerNotificationContract
     */
    public function getServerNotification(): ServerNotificationContract
    {
        return $this->serverNotification;
    }

    /**
     * @return SubscriptionContract
     */
    public function getSubscription(): SubscriptionContract
    {
        return $this->serverNotification->getSubscription();
    }

    /**
     * @return string
     */
    public function getSubscriptionId(): string
    {
        return $this->getSubscription()->getItemId();
    }

    /**
     * @return string
     */
    public function getSubscriptionIdentifier(): string
    {
        return $this->getSubscription()->getUniqueIdentifier();
    }
}
