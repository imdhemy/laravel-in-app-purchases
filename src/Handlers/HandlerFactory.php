<?php

namespace Imdhemy\Purchases\Handlers;

use Exception;
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
     * @throws Exception
     */
    public function create(string $provider): NotificationHandlerContract
    {
        switch ($provider) {
            case 'google-play':
                return $this->application->make(GooglePlayNotificationHandler::class);
            case 'app-store':
                return $this->application->make(AppStoreNotificationHandler::class);
            default:
                throw new Exception(sprintf('Invalid provider: {%s}', $provider));
        }
    }
}
