<?php


namespace Imdhemy\Purchases\Http\Controllers;

use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionPurchased;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;

class DeveloperNotificationController extends Controller
{
    /**
     * @param GoogleDeveloperNotificationRequest $request
     */
    public function google(GoogleDeveloperNotificationRequest $request)
    {
        $data = $request->getData();
        $developerNotification = DeveloperNotification::parse($data);
        event(new SubscriptionPurchased($developerNotification, $request));
    }
}
