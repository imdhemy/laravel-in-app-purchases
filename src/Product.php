<?php

declare(strict_types=1);

namespace Imdhemy\Purchases;

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
    protected string $itemId = '';

    protected string $token = '';

    protected ?ClientInterface $client = null;

    protected string $packageName = '';

    protected string $receiptData = '';

    protected string $password = '';

    public function googlePlay(?ClientInterface $client = null): self
    {
        $this->client = $client ?? GooglePlayClientFactory::create([GooglePlayClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->packageName = (string)config('liap.google_play_package_name');

        return $this;
    }

    public function appStore(?ClientInterface $client = null): self
    {
        $sandbox = (bool)config('liap.appstore_sandbox');

        $this->client = $client ?? AppStoreClientFactory::create($sandbox);
        $this->password = (string)config('liap.appstore_password');

        return $this;
    }

    public function packageName(string $packageName): self
    {
        $this->packageName = $packageName;

        return $this;
    }

    public function id(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function get(): ProductPurchase
    {
        return $this->createProduct()->get();
    }

    public function createProduct(): GooglePlayProduct
    {
        assert(! is_null($this->client));

        return new GooglePlayProduct(
            $this->client,
            $this->packageName,
            $this->itemId,
            $this->token
        );
    }

    /**
     * @throws GuzzleException
     */
    public function acknowledge(?string $developerPayload = null): EmptyResponse
    {
        return $this->createProduct()->acknowledge($developerPayload);
    }

    /**
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verifyReceipt(): ReceiptResponse
    {
        assert(! is_null($this->client));

        return (new Verifier($this->client, $this->receiptData, $this->password))->verify();
    }

    /**
     * @return $this
     */
    public function receiptData(string $receiptData): self
    {
        $this->receiptData = $receiptData;

        return $this;
    }

    /**
     * @return $this
     */
    public function password(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
