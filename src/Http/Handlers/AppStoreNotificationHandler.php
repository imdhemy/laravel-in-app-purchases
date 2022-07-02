<?php

namespace Imdhemy\Purchases\Http\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use Imdhemy\Purchases\Events\AppStore\EventFactory as AppStoreEventFactory;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;

/**
 * App Store server notification handler
 *
 * Handles notifications sent by App store.
 * Dispatches the App store subscription event related to the notification type.
 */
class AppStoreNotificationHandler implements NotificationHandlerContract
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Executes the handler
     */
    public function execute()
    {
        $attributes = $this->request->all();
        $serverNotification = ServerNotification::fromArray($attributes);
        $appStoreNotification = new AppStoreServerNotification($serverNotification);

        if ($appStoreNotification->isTest()) {
            Log::info('AppStore Test Notification');
        }

        $event = AppStoreEventFactory::create($appStoreNotification);
        event($event);
    }
}
