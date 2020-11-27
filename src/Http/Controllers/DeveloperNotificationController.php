<?php


namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;

class DeveloperNotificationController extends Controller
{
    /**
     * @param GoogleDeveloperNotificationRequest $request
     */
    public function google(GoogleDeveloperNotificationRequest $request)
    {
        $data = $request->getData();
        $developerNotification = DeveloperNotification::parse($data);
        $googleNotification = new GoogleServerNotification($developerNotification);

        if ($googleNotification->isTest()) {
            $version = $developerNotification->getTestNotification()->getVersion();
            Log::info(sprintf("Google Play Test Notification, version: %s", $version));
        }

        if ($developerNotification->isSubscriptionNotification()) {
            $event = EventFactory::create($googleNotification);
            event($event);
        }
    }
}
