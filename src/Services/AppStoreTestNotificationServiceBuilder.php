<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Services;

use GuzzleHttp\ClientInterface;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Jws\AppStoreJwsGenerator;
use Imdhemy\AppStore\Jws\GeneratorConfig;
use Imdhemy\AppStore\Jws\Issuer;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\ServerNotifications\TestNotificationService;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * This class is used to build AppStoreTestNotificationService.
 */
class AppStoreTestNotificationServiceBuilder
{
    protected bool $sandbox = false;

    private ?string $issuerId = null;

    private ?string $bundleId = null;

    private ?string $privateKeyId = null;

    private ?string $privateKey = null;

    /**
     * Builds a new instance of AppStoreTestNotificationService.
     */
    public function build(): AppStoreTestNotificationService
    {
        assert(is_string($this->issuerId) && ! empty($this->issuerId));
        assert(is_string($this->bundleId) && ! empty($this->bundleId));
        assert(is_string($this->privateKeyId) && ! empty($this->privateKeyId));
        assert(is_string($this->privateKey) && ! empty($this->privateKey));

        $config = GeneratorConfig::forAppStore(
            new Issuer(
                $this->issuerId,
                $this->bundleId,
                new Key($this->privateKeyId, InMemory::plainText($this->privateKey)),
                new Sha256()
            ),
        );
        $jwsGenerator = new AppStoreJwsGenerator($config);

        $client = $this->createClient();

        $service = new TestNotificationService($client, $jwsGenerator);

        return new AppStoreTestNotificationService($service);
    }

    /**
     * @return $this
     */
    public function issuerId(?string $issuerId): self
    {
        $this->issuerId = $issuerId;

        return $this;
    }

    /**
     * @return $this
     */
    public function bundleId(?string $bundleId): self
    {
        $this->bundleId = $bundleId;

        return $this;
    }

    /**
     * @return $this
     */
    public function privateKeyId(?string $privateKeyId): self
    {
        $this->privateKeyId = $privateKeyId;

        return $this;
    }

    /**
     * @return $this
     */
    public function privateKey(?string $privateKey): self
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * @return $this
     */
    public function sandbox(bool $sandbox): self
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    protected function createClient(): ClientInterface
    {
        $baseURI = $this->sandbox ? ClientFactory::STORE_KIT_SANDBOX_URI : ClientFactory::STORE_KIT_PRODUCTION_URI;

        return ClientFactory::create($this->sandbox, ['base_uri' => $baseURI]);
    }
}
