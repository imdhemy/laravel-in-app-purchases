<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\Events\AppStore\EventFactory as AppStoreEventFactory;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;

/**
 * App Store server notification handler
 *
 * Handles notifications sent by App store.
 * Dispatches the App store subscription event related to the notification type.
 */
class AppStoreNotificationHandler extends AbstractNotificationHandler
{
    /**
     * @inheritDoc
     * @return void
     */
    protected function handle()
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

    /**
     * @return string[][]
     */
    protected function rules(): array
    {
        return [
          'unified_receipt' => ['array', 'required'],
          'unified_receipt.latest_receipt' => ['required'],
          'unified_receipt.latest_receipt_info' => ['required', 'array'],
          'notification_type' => ['required'],
        ];
    }
}
