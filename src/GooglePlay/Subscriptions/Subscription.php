<?php


namespace Imdhemy\Purchases\GooglePlay\Subscriptions;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\Exceptions\CouldNotPersist;
use Imdhemy\Purchases\GooglePlay\ClientFactory;
use Imdhemy\Purchases\GooglePlay\Contracts\CheckerInterface;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;
use Imdhemy\Purchases\Models\PurchaseLog;

/**
 * Class Subscription
 * @package Imdhemy\Purchases\GooglePlay
 */
class Subscription implements CheckerInterface
{
    const URI_FORMAT = "androidpublisher/v3/applications/%s/purchases/subscriptions/%s/tokens/%s";
    const PAYMENT_STATE_RECEIVED = 1;
    const PAYMENT_STATE_FREE_TRIAL = 2;
    const PURCHASE_TYPE_TEST = 0;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Response
     */
    private $response;

    /**
     * Subscription constructor.
     * @param string $id
     * @param string $token
     * @param Client $client
     */
    private function __construct(string $id, string $token, Client $client)
    {
        $this->id = $id;
        $this->token = $token;
        $this->client = $client;
    }

    /**
     * @param string $id
     * @param string $token
     * @return static
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public static function check(string $id, string $token): self
    {
        if (is_null(static::getPackageName())) {
            throw CouldNotCreateSubscription::googlePlayPackageNotSet();
        }

        $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);

        return new self($id, $token, $client);
    }

    /**
     * @return Response
     */
    public function getResponse(): ResponseInterface
    {
        if (is_null($this->response)) {
            $content = $this->client->get($this->getUri())->getBody()->getContents();
            $attributes = json_decode($content, true);
            $attributes['purchaseToken'] = $this->token;
            $attributes['itemId'] = $this->id;
            $this->response = Response::fromArray($attributes);
        }

        return $this->response;
    }

    /**
     * @return string|null
     */
    private static function getPackageName(): ?string
    {
        return config('purchases.google_play_package');
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI_FORMAT, $this->getPackageName(), $this->id, $this->token);
    }

    /**
     * @return PurchaseLog
     */
    public function toLog(): PurchaseLog
    {
        return PurchaseLog::fromResponse($this->getResponse());
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->toLog()->isUnique();
    }

    /**
     * @return PurchaseLog
     * @throws CouldNotPersist
     */
    public function persist(): PurchaseLog
    {
        if ($this->isUnique()) {
            $purchase = $this->toLog();
            $purchase->save();

            return $purchase;
        }

        throw CouldNotPersist::purchaseNotUnique();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->isExpired() || ! $this->isUnique()) {
            return  false;
        }

        return $this->isTesting() || $this->isValidPayment();
    }

    /**
     * @return bool
     */
    public function isTesting(): bool
    {
        $purchaseType = $this->getResponse()->getPurchaseType();

        return $purchaseType === self::PURCHASE_TYPE_TEST;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        $response = $this->getResponse();

        return Carbon::createFromTimestampMs($response->getExpiryTimeMillis())->isPast();
    }

    /**
     * @return bool
     */
    private function isValidPayment(): bool
    {
        $paymentState = $this->getResponse()->getPaymentState();

        return $paymentState === self::PAYMENT_STATE_RECEIVED || $paymentState === self::PAYMENT_STATE_FREE_TRIAL;
    }

    /**
     * @return bool
     */
    public function isAutoRenewing(): bool
    {
        return $this->getResponse()->isAutoRenewing();
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->getResponse()->getUserCancellationTimeMillis() > -1;
    }
}
