<?php

namespace Imdhemy\Purchases\Contracts;

use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\Purchases\ValueObjects\Time;

/**
 * Interface SubscriptionContract
 *
 * @package Imdhemy\Purchases\Events\Contracts
 */
interface SubscriptionContract
{
    // List of providers
    public const PROVIDER_APP_STORE = 'app_store';
    public const PROVIDER_GOOGLE_PLAY = 'google_play';

    /**
     * @return Time
     */
    public function getExpiryTime(): Time;

    /**
     * @return string
     */
    public function getItemId(): string;

    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @return string
     */
    public function getUniqueIdentifier(): string;

    /**
     * @return mixed|SubscriptionPurchase|ReceiptResponse|V2DecodedPayload
     */
    public function getProviderRepresentation();
}
