<?php

namespace Imdhemy\Purchases\Tests\GooglePlay;

use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\Exceptions\CouldNotCreateSubscription;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Response;
use Imdhemy\Purchases\GooglePlay\Subscriptions\Subscription;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class SubscriptionTest
 * @package Imdhemy\Purchases\Tests\GooglePlay
 */
class SubscriptionTest extends TestCase
{
    /**
     * @test
     * @throws CouldNotCreateSubscription
     * @throws CouldNotCreateGoogleClient
     */
    public function it_can_get_subscription_response()
    {
        $id = 'week_premium';
        $token = 'ghpmfmednnbjkcheljjpdnbn.AO-J1OzOqWsD57dURPVrKYh2Qv-t5Lx9VJtCFLdxMovzAgfdF1CwX35AbH3RYRhAMqApdlgLvw7v1Eog43rWYGhGXODl9_Ir9O2YqcXqLSPM7ojuVr9mpcmUha2LZf3YaCbowk1UJfuc';
        $response = Subscription::check($id, $token)->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }
}
