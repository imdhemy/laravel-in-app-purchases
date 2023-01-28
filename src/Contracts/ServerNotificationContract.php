<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Contracts;

use GuzzleHttp\ClientInterface;

/**
 * Interface ServerNotificationContract.
 */
interface ServerNotificationContract
{
    public const PROVIDER_GOOGLE_PLAY = 'google_play';
    public const PROVIDER_APP_STORE = 'app_store';

    /**
     * Gets the notification type.
     */
    public function getType(): string;

    /**
     * Gets the subscription associated with the notification.
     */
    public function getSubscription(?ClientInterface $client = null): SubscriptionContract;

    /**
     * Returns true if the notification is a test notification.
     */
    public function isTest(): bool;

    /**
     * Gets the application bundle.
     */
    public function getBundle(): string;

    /**
     * Gets the notification payload.
     */
    public function getPayload(): array;

    /**
     * Gets the notification provider.
     */
    public function getProvider(): string;
}
