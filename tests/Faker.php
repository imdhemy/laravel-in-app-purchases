<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests;

use Faker\Generator;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use JsonException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use RuntimeException;

/**
 * Class Faker
 * This class is a wrapper for the Faker library.
 *
 * @mixin Generator
 */
class Faker
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function __call($name, $arguments)
    {
        return $this->faker->$name(...$arguments);
    }

    /**
     * Generates a Google subscription notification with the given data.
     */
    public function googleSubscriptionNotification(array $data = []): string
    {
        $subscriptionNotification = array_merge([
            'version' => '1.0',
            'notificationType' => 1,
            'purchaseToken' => 'some-purchase-token',
            'subscriptionId' => 'some-subscription-id',
        ], $data);

        $data = [
            'version' => '1.0',
            'packageName' => 'com.some.thing',
            'eventTimeMillis' => (string)time(),
            'subscriptionNotification' => $subscriptionNotification,
        ];

        return $this->base64Encode($data);
    }

    /**
     * Generates a Google test notification with the given data.
     */
    public function googleTestNotification(array $data = []): string
    {
        $testNotification = array_merge(['version' => '1.0'], $data);

        $data = [
            'version' => '1.0',
            'packageName' => 'com.some.thing',
            'eventTimeMillis' => (string)time(),
            'testNotification' => $testNotification,
        ];

        return $this->base64Encode($data);
    }

    /**
     * Generates an App Store subscription notification with the given data.
     */
    public function appStoreTestNotification(array $data = []): Token
    {
        $alg = new Sha256();
        $x5cJson = file_get_contents(__DIR__.'/Doubles/fixtures/x5c-chain.json');
        $x5c = $this->jsonDecode($x5cJson);

        $data = [
            'notificationType' => 'TEST',
            'notificationUUID' => $this->faker->uuid(),
            'data' => array_merge([
                'bundleId' => 'com.some.thing',
                'environment' => 'Sandbox',
            ], $data),
            'version' => '2.0',
            'signedDate' => time() * 1000,
        ];

        $fakeSignKey = InMemory::plainText($this->generateECPrivateKey());

        return (new JwtFacade())->issue($alg, $fakeSignKey, static function (Builder $builder) use ($data, $x5c) {
            $builder->withHeader('x5c', $x5c);

            foreach ($data as $key => $value) {
                $builder->withClaim($key, $value);
            }

            return $builder;
        });
    }

    /**
     * Generates an App Store server notification v2 with the given data.
     */
    public function appStoreNotification(array $data = [], ?string $notificationType = null): Token
    {
        $alg = new Sha256();
        $x5cJson = file_get_contents(__DIR__.'/Doubles/fixtures/x5c-chain.json');
        $x5c = $this->jsonDecode($x5cJson);

        $data = [
            'notificationType' => $notificationType ?? V2DecodedPayload::TYPE_SUBSCRIBED,
            'notificationUUID' => $this->faker->uuid(),
            'data' => array_merge([
                'bundleId' => 'com.some.thing',
                'environment' => 'Sandbox',
            ], $data),
            'version' => '2.0',
            'signedDate' => time() * 1000,
        ];

        $fakeSignKey = InMemory::plainText($this->generateECPrivateKey());

        return (new JwtFacade())->issue($alg, $fakeSignKey, static function (Builder $builder) use ($data, $x5c) {
            $builder->withHeader('x5c', $x5c);

            foreach ($data as $key => $value) {
                $builder->withClaim($key, $value);
            }

            return $builder;
        });
    }

    /**
     * Safe bas64 encoding.
     */
    public function base64Encode($data): string
    {
        try {
            return base64_encode(json_encode($data, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Safe JSON decoding.
     */
    public function jsonDecode(string $json): array
    {
        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Generates an EC private key.
     */
    public function generateECPrivateKey(): string
    {
        $config = [
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'prime256v1',
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);

        return $privateKey;
    }
}
