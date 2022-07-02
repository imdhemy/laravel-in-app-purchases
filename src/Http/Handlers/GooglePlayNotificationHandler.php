<?php

namespace Imdhemy\Purchases\Http\Handlers;


use Illuminate\Support\Facades\Log;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory as GooglePlayEventFactory;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

/**
 * Google Play notification handler
 *
 * Handles Real time developer notifications sent by google play.
 * Dispatches the Google Play event related to the notification type.
 */
class GooglePlayNotificationHandler implements NotificationHandlerContract
{
    /**
     * @var GoogleDeveloperNotificationRequest
     */
    private $request;

    /**
     * @param GoogleDeveloperNotificationRequest $request
     */
    public function __construct(GoogleDeveloperNotificationRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Executes the handler
     */
    public function execute()
    {
        $data = $this->request->getData();

        if (!$this->isParsable($data)) {
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

        return !is_null($decodedData);
    }
}
