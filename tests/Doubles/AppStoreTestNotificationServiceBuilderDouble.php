<?php

declare(strict_types=1);

namespace Tests\Doubles;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder;

class AppStoreTestNotificationServiceBuilderDouble extends AppStoreTestNotificationServiceBuilder
{
    public const PRODUCTION_TOKEN = 'production-token';
    public const SANDBOX_TOKEN = 'sandbox-token';

    /**
     * @throws \JsonException
     */
    protected function createClient(): Client
    {
        $token = $this->sandbox ? self::SANDBOX_TOKEN : self::PRODUCTION_TOKEN;

        $body = json_encode(['testNotificationToken' => $token], JSON_THROW_ON_ERROR);

        return ClientFactory::mock(new Response(200, [], $body));
    }
}
