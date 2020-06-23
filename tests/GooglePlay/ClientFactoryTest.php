<?php

namespace Imdhemy\Purchases\Tests\GooglePlay;

use GuzzleHttp\Client;
use Imdhemy\Purchases\Exceptions\CouldNotCreateGoogleClient;
use Imdhemy\Purchases\GooglePlay\ClientFactory;
use Imdhemy\Purchases\Tests\TestCase;

/**
 * Class ClientFactoryTest
 * @package Imdhemy\Purchases\Tests\GooglePlay
 */
class ClientFactoryTest extends TestCase
{
    /**
     * @group requires_env_vars
     * @throws CouldNotCreateGoogleClient
     */
    public function it_creates_http_client()
    {
        $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->assertInstanceOf(Client::class, $client);
    }
}
