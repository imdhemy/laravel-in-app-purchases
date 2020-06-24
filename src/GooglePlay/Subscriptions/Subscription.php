<?php


namespace Imdhemy\Purchases\GooglePlay\Subscriptions;

use GuzzleHttp\Client;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\ClientFactory;
use Imdhemy\Purchases\Tests\Models\SubscriptionPurchase;

/**
 * Class Subscription
 * @package Imdhemy\Purchases\GooglePlay
 */
class Subscription
{
    const URI_FORMAT = "androidpublisher/v3/applications/%s/purchases/subscriptions/%s/tokens/%s";

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
    public function getResponse(): Response
    {
        if (is_null($this->response)) {
            $content = $this->client->get($this->getUri())->getBody()->getContents();
            $this->response = Response::fromArray(json_decode($content, true));
            $this->response->setPurchaseToken($this->token);
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
     * @return SubscriptionPurchase
     */
    public function toPurchase(): SubscriptionPurchase
    {
        return SubscriptionPurchase::fromResponse($this->getResponse());
    }
}
