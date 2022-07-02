<?php

namespace Imdhemy\Purchases\Http\Handlers;

use Illuminate\Foundation\Application;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;

/**
 * Handler factory
 *
 * Creates a notification handler instance based on the provider type
 */
class HandlerFactory
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string $provider
     *
     * @return NotificationHandlerContract
     */
    public function create(string $provider): NotificationHandlerContract
    {
        $abstract = AppStoreNotificationHandler::class;

        if ($provider === 'google-play') {
            $abstract = GooglePlayNotificationHandler::class;
        }

        return $this->application->make($abstract);
    }
}
