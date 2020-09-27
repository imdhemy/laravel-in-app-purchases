<?php


namespace Imdhemy\Purchases\GooglePlay\Products;

use GuzzleHttp\Client;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotPersist;
use Imdhemy\Purchases\GooglePlay\ClientFactory;
use Imdhemy\Purchases\GooglePlay\Contracts\CheckerInterface;
use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;
use Imdhemy\Purchases\Models\PurchaseLog;

/**
 * Class Product
 * @package Imdhemy\Purchases\GooglePlay\Product
 */
class Product implements CheckerInterface
{
    const URI_FORMAT = "androidpublisher/v3/applications/%s/purchases/products/%s/tokens/%s";
    const PURCHASE_STATE_PURCHASED = 0;
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
     * Product constructor.
     * @param string $id
     * @param string $token
     * @param Client $client
     */
    public function __construct(string $id, string $token, Client $client)
    {
        $this->id = $id;
        $this->token = $token;
        $this->client = $client;
    }

    /**
     * @param string $id
     * @param string $token
     * @return static
     * @throws CouldNotCreateGoogleClient
     */
    public static function check(string $id, string $token): self
    {
        return new self($id, $token, ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]));
    }

    /**
     * @return Response
     */
    public function getResponse(): ResponseInterface
    {
        if (is_null($this->response)) {
            $content = $this->client->get($this->getUri())->getBody()->getContents();
            $properties = json_decode($content, true);
            $properties['itemId'] = $this->id;
            $properties['purchaseToken'] = $this->token;
            $this->response = Response::fromArray($properties);
        }

        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        $isValidPurchase = $this->isPurchased() || $this->isTesting();

        return $isValidPurchase && $this->isUnique();
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI_FORMAT, $this->getPackageName(), $this->id, $this->token);
    }

    /**
     * @return string
     */
    private function getPackageName(): string
    {
        return config('purchases.google_play_package');
    }

    /**
     * @return bool
     */
    private function isPurchased(): bool
    {
        return $this->getResponse()->getPurchaseState() === self::PURCHASE_STATE_PURCHASED;
    }

    /**
     * @return bool
     */
    private function isUnique(): bool
    {
        return $this->toLog()->isUnique();
    }

    /**
     * @return PurchaseLog
     */
    private function toLog(): PurchaseLog
    {
        return PurchaseLog::fromResponse($this->getResponse());
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
     * @inheritDoc
     */
    public function isTesting(): bool
    {
        return $this->getResponse()->getPurchaseType() === self::PURCHASE_TYPE_TEST;
    }
}
