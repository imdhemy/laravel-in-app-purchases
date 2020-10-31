<?php


namespace Imdhemy\Purchases;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\Products\Product as GooglePlayProduct;
use Imdhemy\GooglePlay\Products\ProductPurchase;

class Product
{
    /**
     * @var string
     */
    protected $itemId;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * Product constructor.
     * @param Client $client
     * @param string $packageName
     */
    public function __construct(Client $client, string $packageName)
    {
        $this->client = $client;
        $this->packageName = $packageName;
    }

    /**
     * @return self
     */
    public function googlePlay(): self
    {
        return $this;
    }

    /**
     * @param string $itemId
     * @return self
     */
    public function id(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @param string $token
     * @return self
     */
    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return ProductPurchase
     * @throws GuzzleException
     */
    public function get(): ProductPurchase
    {
        return $this->createProduct()->get();
    }

    /**
     * Acknowledges the specified purchase
     * @throws GuzzleException
     */
    public function acknowledge(): void
    {
        $this->createProduct()->acknowledge();
    }

    /**
     * @return GooglePlayProduct
     */
    public function createProduct(): GooglePlayProduct
    {
        return new GooglePlayProduct(
            $this->client,
            $this->packageName,
            $this->itemId,
            $this->token
        );
    }
}
