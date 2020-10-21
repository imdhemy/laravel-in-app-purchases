<?php


namespace Imdhemy\Purchases\Http\Controllers;

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
        $event = EventFactory::create($developerNotification);
        event($event);
    }
}
