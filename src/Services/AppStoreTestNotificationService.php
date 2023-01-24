<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Services;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ServerNotifications\TestNotificationService;
use Psr\Http\Message\ResponseInterface;

/**
 * This class is used to request a test notification from Apple.
 */
final class AppStoreTestNotificationService
{
    private TestNotificationService $service;

    public function __construct(TestNotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws GuzzleException
     */
    public function request(): ResponseInterface
    {
        return $this->service->request();
    }
}
