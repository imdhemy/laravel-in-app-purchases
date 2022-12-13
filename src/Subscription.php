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
use Imdhemy\GooglePlay\Subscriptions\SubscriptionClient as GooglePlaySubscription;
use Imdhemy\GooglePlay\Subscriptions\SubscriptionPurchase;
use Imdhemy\GooglePlay\ValueObjects\EmptyResponse;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\Subscriptions\AppStoreSubscription;
use Imdhemy\Purchases\Subscriptions\GoogleSubscription;

class Subscription
{
    protected ?string $itemId = null;

    protected ?string $token = null;

    protected ?ClientInterface $client = null;

    protected ?string $packageName = null;

    protected ?string $receiptData = null;

    protected ?string $password = null;

    protected bool $renewalAble = false;

    protected ?SubscriptionPurchase $googleGetResponse = null;

    protected bool $isGoogle = false;

    private ?ReceiptResponse $appStoreResponse = null;

    /**
     * @psalm-suppress PropertyTypeCoercion - The client type is compatible
     */
    public function googlePlay(?ClientInterface $client = null): self
    {
        $this->isGoogle = true;
        $this->client = $client ?? GooglePlayClientFactory::create([GooglePlayClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->packageName = (string)config('liap.google_play_package_name');

        return $this;
    }

    /**
     * @psalm-suppress PropertyTypeCoercion - The client type is compatible
     */
    public function appStore(?ClientInterface $client = null): self
    {
        $this->isGoogle = false;
        $this->client = $client ?? AppStoreClientFactory::create();
        $this->password = (string)config('liap.appstore_password');
        $this->renewalAble = false;

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

    public function packageName(string $packageName): self
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidReceiptException
     */
    public function verifyRenewable(?ClientInterface $sandboxClient = null): ReceiptResponse
    {
        $this->renewalAble = true;

        return $this->verifyReceipt($sandboxClient);
    }

    /**
     * @throws GuzzleException
     * @throws InvalidReceiptException
     */
    public function verifyReceipt(?ClientInterface $sandboxClient = null): ReceiptResponse
    {
        if (is_null($this->appStoreResponse)) {
            assert(! is_null($this->client));
            assert(! is_null($this->receiptData));
            assert(! is_null($this->password));

            $verifier = new Verifier($this->client, $this->receiptData, $this->password);
            $this->appStoreResponse = $verifier->verify($this->renewalAble, $sandboxClient);
        }

        return $this->appStoreResponse;
    }

    public function renewable(): self
    {
        $this->renewalAble = true;

        return $this;
    }

    public function nonRenewable(): self
    {
        $this->renewalAble = false;

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function acknowledge(?string $developerPayload = null): EmptyResponse
    {
        return $this->createSubscription()->acknowledge($developerPayload);
    }

    /**
     * @psalm-suppress PossiblyNullArgument - This method should not be called if params are null
     */
    public function createSubscription(): GooglePlaySubscription
    {
        return new GooglePlaySubscription(
            $this->client,
            $this->packageName,
            $this->itemId,
            $this->token
        );
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

    /**
     * @throws GuzzleException
     * @throws InvalidReceiptException
     *
     * @psalm-suppress  PossiblyNullArgument - This method should not be called if itemId and token are null
     * @psalm-suppress  MixedArgument - We know the type of the latest receipt info
     * @psalm-suppress  PossiblyNullArrayAccess - This method should not be called if the array if empty
     */
    public function toStd(): SubscriptionContract
    {
        if ($this->isGoogle) {
            $response = $this->get();

            return new GoogleSubscription($response, $this->itemId, $this->token);
        }

        $response = $this->verifyReceipt();

        return new AppStoreSubscription($response->getLatestReceiptInfo()[0]);
    }

    /**
     * @throws GuzzleException
     */
    public function get(): SubscriptionPurchase
    {
        if (is_null($this->googleGetResponse)) {
            $this->googleGetResponse = $this->createSubscription()->get();
        }

        return $this->googleGetResponse;
    }
}
