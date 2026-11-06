<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\GooglePlay\Notification;

use DomainException;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\Response;
use InvalidArgumentException;
use LogicException;
use TypeError;
use UnexpectedValueException;

/**
 * Verifies the OpenID Connect token that Pub/Sub attaches to authenticated push requests.
 *
 * @see https://cloud.google.com/pubsub/docs/authenticate-push-subscriptions
 *
 * @internal package infrastructure; not part of the public API
 *
 * @psalm-api
 */
final readonly class PushTokenVerifier
{
    private const string ALGORITHM = 'RS256';
    private const string CERTIFICATES_URL = 'https://www.googleapis.com/oauth2/v3/certs';
    private const string CERTIFICATES_CACHE_KEY = 'liap.google_play.push_token_certificates';
    private const int DEFAULT_CERTIFICATES_TTL = 3600;
    private const int REQUEST_TIMEOUT = 10;
    private const array ISSUERS = ['accounts.google.com', 'https://accounts.google.com'];

    public function __construct(
        private Http $http,
        private Cache $cache,
        #[Config('liap.google_play_push_authentication.audience')]
        private ?string $audience = null,
        #[Config('liap.google_play_push_authentication.service_account_email')]
        private ?string $serviceAccountEmail = null,
    ) {
    }

    /**
     * Failing to retrieve Google's certificates raises an exception instead of rejecting the token, so that
     * the outage is reported and Pub/Sub retries the delivery.
     *
     * @throws LogicException when the expected audience or service account email is not configured
     */
    public function verify(string $token): bool
    {
        if (null === $this->audience || '' === $this->audience) {
            throw new LogicException('A Google Play push authentication audience is required.');
        }

        if (null === $this->serviceAccountEmail || '' === $this->serviceAccountEmail) {
            throw new LogicException('A Google Play push authentication service account email is required.');
        }

        $keys = $this->keys();

        try {
            $claims = JWT::decode($token, $keys);
        } catch (DomainException|InvalidArgumentException|TypeError|UnexpectedValueException) {
            return false;
        }

        return $this->audience === ($claims->aud ?? null)
            && in_array($claims->iss ?? null, self::ISSUERS, true)
            && $this->serviceAccountEmail === ($claims->email ?? null)
            && in_array($claims->email_verified ?? null, [true, 'true'], true);
    }

    /**
     * @return array<string, Key>
     */
    private function keys(): array
    {
        /** @var array<string, mixed>|null $certificates */
        $certificates = $this->cache->get(self::CERTIFICATES_CACHE_KEY);

        if (null !== $certificates) {
            return JWK::parseKeySet($certificates, self::ALGORITHM);
        }

        $response = $this->http->timeout(self::REQUEST_TIMEOUT)->get(self::CERTIFICATES_URL)->throw();
        $certificates = (array)$response->json();
        $keys = JWK::parseKeySet($certificates, self::ALGORITHM);

        $this->cache->put(self::CERTIFICATES_CACHE_KEY, $certificates, $this->certificatesTtl($response));

        return $keys;
    }

    private function certificatesTtl(Response $response): int
    {
        if (1 === preg_match('/max-age=(\d+)/', $response->header('Cache-Control'), $matches)) {
            return (int)$matches[1];
        }

        return self::DEFAULT_CERTIFICATES_TTL;
    }
}
