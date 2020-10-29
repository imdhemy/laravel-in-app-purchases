<?php


namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\GooglePlay\EventFactory;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;
use ReflectionException;

class DeveloperNotificationController extends Controller
{
    /**
     * @param GoogleDeveloperNotificationRequest $request
     * @throws ReflectionException
     */
    public function google(GoogleDeveloperNotificationRequest $request)
    {
        $data = $request->getData();
        $developerNotification = DeveloperNotification::parse($data);

        if ($developerNotification->isTestNotification()) {
            $version = $developerNotification->getTestNotification()->getVersion();
            Log::info(sprintf("Google Play Test Notification, version: %s", $version));
        }

        if ($developerNotification->isSubscriptionNotification()) {
            $event = EventFactory::create($developerNotification);
            event($event);
        }
    }
}
