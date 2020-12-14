<?php


namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\AppStore\EventFactory as AppStoreEventFactory;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory as GooglePlayEventFactory;
use Imdhemy\Purchases\Http\Requests\AppStoreServerNotificationRequest;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;
use Imdhemy\Purchases\ServerNotifications\AppStoreServerNotification;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

class ServerNotificationController extends Controller
{
    /**
     * @param GoogleDeveloperNotificationRequest $request
     */
    public function google(GoogleDeveloperNotificationRequest $request)
    {
        $data = $request->getData();

        if (! $this->isParsable($data)) {
            Log::info(sprintf("Google Play malformed RTDN: %s", json_encode($request->all())));

            return;
        }

        $developerNotification = DeveloperNotification::parse($data);
        $googleNotification = new GoogleServerNotification($developerNotification);

        if ($googleNotification->isTest()) {
            $version = $developerNotification->getTestNotification()->getVersion();
            Log::info(sprintf("Google Play Test Notification, version: %s", $version));
        }

        if ($developerNotification->isSubscriptionNotification()) {
            $event = GooglePlayEventFactory::create($googleNotification);
            event($event);
        }
    }

    /**
     * @param AppStoreServerNotificationRequest $request
     */
    public function apple(AppStoreServerNotificationRequest $request)
    {
        $attributes = $request->all();
        $serverNotification = ServerNotification::fromArray($attributes);
        $appStoreNotification = new AppStoreServerNotification($serverNotification);

        if ($appStoreNotification->isTest()) {
            Log::info("AppStore Test Notification");
        }

        $event = AppStoreEventFactory::create($appStoreNotification);
        event($event);
    }

    /**
     * @param string $data
     * @return bool
     */
    protected function isParsable(string $data): bool
    {
        $decodedData = json_decode(base64_decode($data), true);

        return ! is_null($decodedData);
    }
}
