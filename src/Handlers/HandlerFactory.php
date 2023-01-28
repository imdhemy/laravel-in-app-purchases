<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use RuntimeException;

/**
 * Handler factory.
 *
 * Creates a notification handler instance based on the provider type
 */
class HandlerFactory
{
    protected Application $application;

    protected Request $request;

    public function __construct(Application $application, Request $request)
    {
        $this->application = $application;

        $this->request = $request;
    }

    /**
     * @psalm-suppress MixedInferredReturnType - Laravel's make() method returns mixed
     * @psalm-suppress MixedReturnStatement - We are returning a NotificationHandlerContract
     */
    public function create(): NotificationHandlerContract
    {
        $provider = (string)$this->request->get('provider');

        if ('app-store' === $provider && $this->request->has('signedPayload')) {
            $provider = 'app-store-v2';
        }

        switch ($provider) {
            case 'google-play':
                return $this->application->make(GooglePlayNotificationHandler::class);
            case 'app-store':
                return $this->application->make(AppStoreNotificationHandler::class);
            case 'app-store-v2':
                return $this->application->make(AppStoreV2NotificationHandler::class);
            default:
                throw new RuntimeException(sprintf('Invalid provider: {%s}', $provider));
        }
    }
}
