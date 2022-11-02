<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Services;

use GuzzleHttp\Client;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Jws\AppStoreJwsGenerator;
use Imdhemy\AppStore\Jws\GeneratorConfig;
use Imdhemy\AppStore\Jws\Issuer;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\ServerNotifications\TestNotificationService;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * This class is used to build AppStoreTestNotificationService
 */
class AppStoreTestNotificationServiceBuilder
{
    /**
     * @var bool
     */
    protected bool $sandbox = false;

    /**
     * @var string|null
     */
    private ?string $issuerId = null;

    /**
     * @var string|null
     */
    private ?string $bundleId = null;

    /**
     * @var string|null
     */
    private ?string $privateKeyId = null;

    /**
     * @var string|null
     */
    private ?string $privateKey = null;

    /**
     * Builds a new instance of AppStoreTestNotificationService
     *
     * @return AppStoreTestNotificationService
     */
    public function build(): AppStoreTestNotificationService
    {
        $config = GeneratorConfig::forAppStore(
            new Issuer(
                $this->issuerId,
                $this->bundleId,
                new Key($this->privateKeyId, InMemory::plainText($this->privateKey)),
                Sha256::create()
            ),
        );
        $jwsGenerator = new AppStoreJwsGenerator($config);

        $client = $this->createClient();

        $service = new TestNotificationService($client, $jwsGenerator);

        return new AppStoreTestNotificationService($service);
    }

    /**
     * @param string|null $issuerId
     *
     * @return $this
     */
    public function issuerId(?string $issuerId): self
    {
        $this->issuerId = $issuerId;

        return $this;
    }

    /**
     * @param string|null $bundleId
     *
     * @return $this
     */
    public function bundleId(?string $bundleId): self
    {
        $this->bundleId = $bundleId;

        return $this;
    }

    /**
     * @param string|null $privateKeyId
     *
     * @return $this
     */
    public function privateKeyId(?string $privateKeyId): self
    {
        $this->privateKeyId = $privateKeyId;

        return $this;
    }

    /**
     * @param string|null $privateKey
     *
     * @return $this
     */
    public function privateKey(?string $privateKey): self
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * @param bool $sandbox
     *
     * @return $this
     */
    public function sandbox(bool $sandbox): self
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    /**
     * @return Client
     */
    protected function createClient(): Client
    {
        $baseURI = $this->sandbox ? ClientFactory::STORE_KIT_SANDBOX_URI : ClientFactory::STORE_KIT_PRODUCTION_URI;

        return ClientFactory::create($this->sandbox, ['base_uri' => $baseURI]);
    }
}
