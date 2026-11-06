<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Feature;

use Closure;
use Firebase\JWT\JWT;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Imdhemy\Purchases\Domain\Event\GooglePlayNotificationReceivedEvent;
use Imdhemy\Purchases\Tests\TestCase;
use LogicException;
use OpenSSLAsymmetricKey;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use UnexpectedValueException;

final class AuthenticateGooglePlayPushFeatureTest extends TestCase
{
    private const string AUDIENCE = 'https://example.com/liap/notifications';
    private const string SERVICE_ACCOUNT_EMAIL = 'push@example.iam.gserviceaccount.com';
    private const string KEY_ID = 'google-key';
    private const string CERTIFICATES_URL = 'https://www.googleapis.com/oauth2/v3/certs';

    private static ?OpenSSLAsymmetricKey $googleKey = null;
    private static ?OpenSSLAsymmetricKey $otherKey = null;

    private PromiseInterface|Closure $certificatesResponse;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'cache.default' => 'array',
            'liap.google_play_push_authentication' => [
                'enabled' => true,
                'audience' => self::AUDIENCE,
                'service_account_email' => self::SERVICE_ACCOUNT_EMAIL,
            ],
        ]);

        Event::fake();
        Http::preventStrayRequests();
        Http::fake([self::CERTIFICATES_URL => fn (Request $request) => value($this->certificatesResponse, $request)]);
        $this->certificatesResponse = Http::response($this->certificates());
    }

    #[Test]
    #[DataProvider('validClaims')]
    public function it_accepts_a_token_issued_by_google_for_the_configured_subscription(array $claims): void
    {
        $this->push($this->token($claims))->assertStatus(200);

        Event::assertDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    /**
     * @return array<string, array{array<string, mixed>}>
     */
    public static function validClaims(): array
    {
        return [
            'issuer with scheme' => [['iss' => 'https://accounts.google.com']],
            'issuer without scheme' => [['iss' => 'accounts.google.com']],
            'email verified as a string' => [['email_verified' => 'true']],
        ];
    }

    #[Test]
    public function it_rejects_a_request_without_a_token_before_retrieving_certificates(): void
    {
        $this->push()->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
        Http::assertNothingSent();
    }

    #[Test]
    #[DataProvider('invalidClaims')]
    public function it_rejects_a_token_with_invalid_claims(array $claims): void
    {
        $this->push($this->token($claims))->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    /**
     * @return array<string, array{array<string, mixed>}>
     */
    public static function invalidClaims(): array
    {
        return [
            'other audience' => [['aud' => 'https://attacker.example.com']],
            'missing audience' => [['aud' => null]],
            'audience list' => [['aud' => [self::AUDIENCE]]],
            'other issuer' => [['iss' => 'https://attacker.example.com']],
            'missing issuer' => [['iss' => null]],
            'other service account' => [['email' => 'attacker@example.iam.gserviceaccount.com']],
            'missing email' => [['email' => null]],
            'unverified email' => [['email_verified' => false]],
            'unverified email as a string' => [['email_verified' => 'false']],
            'missing email verification' => [['email_verified' => null]],
            'expired' => [['iat' => time() - 7200, 'exp' => time() - 3600]],
            'issued in the future' => [['iat' => time() + 3600]],
        ];
    }

    #[Test]
    public function it_rejects_a_token_signed_with_another_key(): void
    {
        $token = JWT::encode($this->claims(), self::otherKey(), 'RS256', self::KEY_ID);

        $this->push($token)->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    #[Test]
    public function it_rejects_a_token_with_an_unknown_key_id(): void
    {
        $token = JWT::encode($this->claims(), self::googleKey(), 'RS256', 'unknown-key');

        $this->push($token)->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    #[Test]
    public function it_rejects_a_token_signed_with_the_public_key_as_a_shared_secret(): void
    {
        $publicKey = openssl_pkey_get_details(self::googleKey())['key'];
        $token = JWT::encode($this->claims(), $publicKey, 'HS256', self::KEY_ID);

        $this->push($token)->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    #[Test]
    #[DataProvider('malformedTokens')]
    public function it_rejects_a_malformed_token(string $token): void
    {
        $this->push($token)->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function malformedTokens(): array
    {
        $encode = static fn (array $data): string => JWT::urlsafeB64Encode(json_encode($data, JSON_THROW_ON_ERROR));
        $unsigned = static fn (array $header): string => sprintf('%s.%s.c2ln', $encode($header), $encode(['sub' => '1']));

        return [
            'not a token' => ['not-a-token'],
            'wrong segments' => ['a.b'],
            'invalid header' => ['bm90LWpzb24.bm90LWpzb24.c2ln'],
            'unsigned' => [$unsigned(['alg' => 'none', 'kid' => self::KEY_ID])],
            'algorithm list' => [$unsigned(['alg' => ['RS256'], 'kid' => self::KEY_ID])],
            'key id list' => [$unsigned(['alg' => 'RS256', 'kid' => [self::KEY_ID]])],
            'numeric key id' => [$unsigned(['alg' => 'RS256', 'kid' => 1])],
        ];
    }

    #[Test]
    public function it_retrieves_certificates_once_and_caches_them_for_their_max_age(): void
    {
        $this->certificatesResponse = Http::response(
            $this->certificates(),
            200,
            ['Cache-Control' => 'public, max-age=120, must-revalidate'],
        );

        $this->push($this->token())->assertStatus(200);
        $this->push($this->token())->assertStatus(200);
        Http::assertSentCount(1);

        $this->travel(121)->seconds();

        $this->push($this->token())->assertStatus(200);
        Http::assertSentCount(2);
    }

    #[Test]
    public function it_caches_certificates_for_an_hour_without_a_max_age(): void
    {
        $this->push($this->token())->assertStatus(200);

        $this->travel(3599)->seconds();
        $this->push($this->token())->assertStatus(200);
        Http::assertSentCount(1);

        $this->travel(2)->seconds();
        $this->push($this->token())->assertStatus(200);
        Http::assertSentCount(2);
    }

    #[Test]
    public function it_does_not_reject_a_delivery_when_certificates_are_unavailable(): void
    {
        $this->certificatesResponse = Http::response(status: 503);
        $this->withoutExceptionHandling();

        $this->expectException(RequestException::class);

        $this->push($this->token());
    }

    #[Test]
    public function it_does_not_reject_a_delivery_when_google_cannot_be_reached(): void
    {
        $this->certificatesResponse = Http::failedConnection();
        $this->withoutExceptionHandling();

        $this->expectException(ConnectionException::class);

        $this->push($this->token());
    }

    #[Test]
    public function it_does_not_cache_an_invalid_certificates_response(): void
    {
        $this->certificatesResponse = Http::response(['unexpected' => 'body']);
        $this->withoutExceptionHandling();

        try {
            $this->push($this->token());
            $this->fail('An invalid certificates response must raise an exception.');
        } catch (UnexpectedValueException) {
            $this->assertFalse(Cache::has('liap.google_play.push_token_certificates'));
        }
    }

    #[Test]
    #[DataProvider('incompleteConfiguration')]
    public function it_fails_closed_when_the_configuration_is_incomplete(string $key, ?string $value): void
    {
        config(["liap.google_play_push_authentication.$key" => $value]);
        $this->withoutExceptionHandling();

        $this->expectException(LogicException::class);

        $this->push($this->token());
    }

    /**
     * @return array<string, array{string, ?string}>
     */
    public static function incompleteConfiguration(): array
    {
        return [
            'missing audience' => ['audience', null],
            'empty audience' => ['audience', ''],
            'missing service account email' => ['service_account_email', null],
            'empty service account email' => ['service_account_email', ''],
        ];
    }

    #[Test]
    public function it_does_not_authenticate_push_requests_by_default(): void
    {
        config(['liap.google_play_push_authentication.enabled' => false]);

        $this->push()->assertStatus(200);

        Event::assertDispatched(GooglePlayNotificationReceivedEvent::class);
        Http::assertNothingSent();
    }

    #[Test]
    public function it_still_requires_a_valid_url_signature_when_signing_is_enabled(): void
    {
        config(['liap.routing.signed' => true]);

        $this->push($this->token())->assertStatus(403);

        Event::assertNotDispatched(GooglePlayNotificationReceivedEvent::class);
    }

    private function push(?string $token = null): TestResponse
    {
        $headers = null === $token ? [] : ['Authorization' => "Bearer $token"];

        return $this->post(
            '/liap/notifications?provider=google-play',
            ['message' => ['data' => $this->faker->googleTestNotification()]],
            $headers,
        );
    }

    /**
     * @param array<string, mixed> $claims Claims overriding the valid defaults; a null value removes the claim
     */
    private function token(array $claims = []): string
    {
        return JWT::encode($this->claims($claims), self::googleKey(), 'RS256', self::KEY_ID);
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function claims(array $overrides = []): array
    {
        $claims = array_merge([
            'aud' => self::AUDIENCE,
            'iss' => 'https://accounts.google.com',
            'email' => self::SERVICE_ACCOUNT_EMAIL,
            'email_verified' => true,
            'iat' => time(),
            'exp' => time() + 3600,
        ], $overrides);

        return array_filter($claims, static fn (mixed $claim): bool => null !== $claim);
    }

    /**
     * @return array{keys: list<array<string, string>>}
     */
    private function certificates(): array
    {
        $rsa = openssl_pkey_get_details(self::googleKey())['rsa'];

        return [
            'keys' => [[
                'kty' => 'RSA',
                'use' => 'sig',
                'alg' => 'RS256',
                'kid' => self::KEY_ID,
                'n' => JWT::urlsafeB64Encode($rsa['n']),
                'e' => JWT::urlsafeB64Encode($rsa['e']),
            ]],
        ];
    }

    private static function googleKey(): OpenSSLAsymmetricKey
    {
        return self::$googleKey ??= self::generateKey();
    }

    private static function otherKey(): OpenSSLAsymmetricKey
    {
        return self::$otherKey ??= self::generateKey();
    }

    private static function generateKey(): OpenSSLAsymmetricKey
    {
        return openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
    }
}
