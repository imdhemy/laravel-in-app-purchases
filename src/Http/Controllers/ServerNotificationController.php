<?php

namespace Imdhemy\Purchases\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Imdhemy\Purchases\Http\Handlers\AppStoreNotificationHandler;
use Imdhemy\Purchases\Http\Handlers\GooglePlayNotificationHandler;
use Imdhemy\Purchases\Http\Requests\AppStoreServerNotificationRequest;
use Imdhemy\Purchases\Http\Requests\GoogleDeveloperNotificationRequest;
use Imdhemy\Purchases\Http\Requests\ServerNotificationRequest;

/**
 * Server notification controller
 *
 * This controller handles the incoming requests by the IAP service providers
 * and dispatches relevant events.
 */
class ServerNotificationController extends Controller
{
    /**
     * Handles the server notification request
     *
     * @param ServerNotificationRequest $request
     *
     * @throws AuthorizationException
     */
    public function __invoke(ServerNotificationRequest $request)
    {
        $provider = $request->getProvider();
        if ($provider === 'google-play') {
            $request = GoogleDeveloperNotificationRequest::createFrom($request);
            $this->google($request);
        } else {
            $request = AppStoreServerNotificationRequest::createFrom($request);
            $this->apple($request);
        }
    }

    /**
     * @param GoogleDeveloperNotificationRequest $request
     *
     * @throws AuthorizationException
     */
    public function google(GoogleDeveloperNotificationRequest $request)
    {
        $handler = new GooglePlayNotificationHandler($request);
        $handler->execute();
    }

    /**
     * @param AppStoreServerNotificationRequest $request
     *
     * @throws AuthorizationException
     */
    public function apple(AppStoreServerNotificationRequest $request)
    {
        $handler = new AppStoreNotificationHandler($request);
        $handler->execute();
    }
}
