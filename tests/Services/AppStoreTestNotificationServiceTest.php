<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Jws\JwsGenerator;
use Imdhemy\AppStore\ServerNotifications\TestNotificationService;
use Imdhemy\Purchases\Services\AppStoreTestNotificationService;
use Imdhemy\Purchases\Tests\TestCase;

class AppStoreTestNotificationServiceTest extends TestCase
{
    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function request(): void
    {
        $history = [];
        $client = ClientFactory::mock(new Response(), $history);
        $service = new TestNotificationService($client, $this->createMock(JwsGenerator::class));

        $sut = new AppStoreTestNotificationService($service);

        $sut->request();

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/inApps/v1/notifications/test', $request->getUri()->getPath());
    }
}
