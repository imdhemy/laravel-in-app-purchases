<?php


namespace Imdhemy\Purchases\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;

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
}
