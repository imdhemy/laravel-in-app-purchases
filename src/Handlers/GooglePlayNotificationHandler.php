<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Support\Facades\Log;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory as GooglePlayEventFactory;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

/**
 * Google Play notification handler
 *
 * Handles Real time developer notifications sent by google play.
 * Dispatches the Google Play event related to the notification type.
 */
class GooglePlayNotificationHandler extends AbstractNotificationHandler
{
    /**
     * @return string[][]
     */
    protected function rules(): array
    {
        return [
          'message' => ['required', 'array'],
          'message.data' => ['required'],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function handle()
    {
        $data = $this->request->get('message')['data'];

        if (! $this->isParsable($data)) {
            Log::info(sprintf('Google Play malformed RTDN: %s', json_encode($this->request->all())));

            return;
        }

        $developerNotification = DeveloperNotification::parse($data);
        $googleNotification = new GoogleServerNotification($developerNotification);

        if ($developerNotification->isTestNotification()) {
            $version = $developerNotification->getPayload()->getVersion();
            Log::info(sprintf('Google Play Test Notification, version: %s', $version));
        }

        if ($developerNotification->getPayload() instanceof SubscriptionNotification) {
            $event = GooglePlayEventFactory::create($googleNotification);
            event($event);
        }
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    protected function isParsable(string $data): bool
    {
        $decodedData = json_decode(base64_decode($data), true);

        return ! is_null($decodedData);
    }
}
