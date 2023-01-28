<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;

/**
 * App Store server notification handler.
 *
 * Handles notifications sent by App store.
 * Dispatches the App store subscription event related to the notification type.
 */
class AppStoreNotificationHandler extends AbstractNotificationHandler
{
    /**
     * @psalm-suppress MissingReturnType - @todo: fix missing return type
     */
    protected function handle()
    {
        $attributes = $this->request->all();
        $serverNotification = ServerNotification::fromArray($attributes);
        $appStoreNotification = new AppStoreServerNotification($serverNotification);

        if ($appStoreNotification->isTest()) {
            Log::info('AppStore Test Notification');
        }

        $event = $this->eventFactory->create($appStoreNotification);
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
