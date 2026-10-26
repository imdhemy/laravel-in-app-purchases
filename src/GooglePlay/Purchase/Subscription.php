<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\GooglePlay\Purchase;

use Illuminate\Container\Attributes\Config;
use Imdhemy\GooglePlay\Domain\Purchase\Entity\SubscriptionPurchase;
use Imdhemy\GooglePlay\Domain\Purchase\Subscription\RevocationContext;
use Imdhemy\GooglePlay\Domain\Purchase\SubscriptionService;
use Imdhemy\GooglePlay\Infrastructure\Http\ClientFactory;
use Imdhemy\GooglePlay\Infrastructure\Transformer\Normalizer;
use Imdhemy\GooglePlay\Infrastructure\Transformer\Serializer;
use LogicException;
use Psr\Http\Client\ClientInterface;

/**
 * Configures a subscription purchase operation independently of other facade calls.
 *
 * @api
 */
final class Subscription
{
    private ?ClientInterface $client = null;

    private ?string $token = null;

    public function __construct(
        #[Config('liap.google_play_package_name')]
        private ?string $packageName = null,
    ) {
    }

    public function client(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function packageName(string $packageName): self
    {
        $this->packageName = $packageName;

        return $this;
    }

    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function get(): SubscriptionPurchase
    {
        [$packageName, $token] = $this->requireTarget();

        return $this->service()->get($packageName, $token);
    }

    /**
     * Revokes the subscription purchase and refunds it as described by the revocation context.
     */
    public function revoke(RevocationContext $revocationContext): void
    {
        [$packageName, $token] = $this->requireTarget();

        $this->service()->revoke($packageName, $token, $revocationContext);
    }

    /**
     * @return array{non-empty-string, non-empty-string}
     */
    private function requireTarget(): array
    {
        if (null === $this->token || '' === $this->token) {
            throw new LogicException('A purchase token is required.');
        }

        if (null === $this->packageName || '' === $this->packageName) {
            throw new LogicException('An application package name is required.');
        }

        return [$this->packageName, $this->token];
    }

    private function service(): SubscriptionService
    {
        return new SubscriptionService(
            $this->client ?? ClientFactory::create(),
            Normalizer::create(),
            Serializer::create(),
        );
    }
}
