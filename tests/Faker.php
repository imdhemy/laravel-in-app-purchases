<?php

namespace Tests;

use Faker\Generator;
use JsonException;
use RuntimeException;

/**
 * Class Faker
 * This class is a wrapper for the Faker library.
 */
class Faker
{
    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->faker->$name(...$arguments);
    }

    /**
     * Generates a Google subscription notification with the given data
     *
     * @param array $data
     *
     * @return string
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
     * Generates a Google test notification with the given data
     *
     * @param array $data
     *
     * @return string
     */
    public function googleTestNotification(array $data = []): string
    {
        $testNotification = array_merge([
            'version' => '1.0',
        ], $data);

        $data = [
            'version' => '1.0',
            'packageName' => 'com.some.thing',
            'eventTimeMillis' => (string)time(),
            'testNotification' => $testNotification,
        ];

        return $this->base64Encode($data);
    }

    /**
     * Safe bas64 encoding
     *
     * @param $data
     *
     * @return string
     */
    public function base64Encode($data): string
    {
        try {
            return base64_encode(json_encode($data, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
