<?php


namespace Imdhemy\Purchases;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory as AppStoreClientFactory;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\Receipts\Verifier;
use Imdhemy\GooglePlay\ClientFactory as GooglePlayClientFactory;
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
     * @var string
     */
    protected $receiptData;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return self
     */
    public function googlePlay(): self
    {
        $this->client = GooglePlayClientFactory::create([GooglePlayClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->packageName = config('purchase.google_play_package_name');

        return $this;
    }

    /**
     * @return self
     */
    public function appStore(): self
    {
        $sandbox = (bool)config('purchase.appstore_sandbox');

        $this->client = AppStoreClientFactory::create($sandbox);
        $this->password = config('purchase.appstore_password');

        return $this;
    }

    /**
     * @param string $packageName
     * @return self
     */
    public function packageName(string $packageName): self
    {
        $this->packageName = $packageName;

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
     * @param string|null $developerPayload
     * @throws GuzzleException
     */
    public function acknowledge(?string $developerPayload = null): void
    {
        $this->createProduct()->acknowledge($developerPayload);
    }

    /**
     * @return ReceiptResponse
     * @throws GuzzleException
     */
    public function verifyReceipt(): ReceiptResponse
    {
        $verifier = new Verifier($this->client, $this->receiptData, $this->password);

        return $verifier->verify();
    }

    /**
     * @param string $receiptData
     * @return $this
     */
    public function receiptData(string $receiptData): self
    {
        $this->receiptData = $receiptData;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function password(string $password): self
    {
        $this->password = $password;

        return $this;
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
