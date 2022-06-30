<?php

namespace Imdhemy\Purchases;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory as AppStoreClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\Receipts\Verifier;
use Imdhemy\GooglePlay\ClientFactory as GooglePlayClientFactory;
use Imdhemy\GooglePlay\Products\ProductClient as GooglePlayProduct;
use Imdhemy\GooglePlay\Products\ProductPurchase;
use Imdhemy\GooglePlay\ValueObjects\EmptyResponse;

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
     * @var string
     */
    protected $receiptData;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param ClientInterface|null $client
     *
     * @return self
     */
    public function googlePlay(?ClientInterface $client = null): self
    {
        $this->client = $client ?? GooglePlayClientFactory::create([GooglePlayClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->packageName = config('liap.google_play_package_name');

        return $this;
    }

    /**
     * @param ClientInterface|null $client
     *
     * @return self
     */
    public function appStore(?ClientInterface $client = null): self
    {
        $sandbox = (bool)config('liap.appstore_sandbox');

        $this->client = $client ?? AppStoreClientFactory::create($sandbox);
        $this->password = config('liap.appstore_password');

        return $this;
    }

    /**
     * @param string $packageName
     *
     * @return self
     */
    public function packageName(string $packageName): self
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * @param string $itemId
     *
     * @return self
     */
    public function id(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @param string $token
     *
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

    /**
     * @param string|null $developerPayload
     *
     * @return EmptyResponse
     * @throws GuzzleException
     */
    public function acknowledge(?string $developerPayload = null): EmptyResponse
    {
        return $this->createProduct()->acknowledge($developerPayload);
    }

    /**
     * @return ReceiptResponse
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verifyReceipt(): ReceiptResponse
    {
        $verifier = new Verifier($this->client, $this->receiptData, $this->password);

        return $verifier->verify();
    }

    /**
     * @param string $receiptData
     *
     * @return $this
     */
    public function receiptData(string $receiptData): self
    {
        $this->receiptData = $receiptData;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function password(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
