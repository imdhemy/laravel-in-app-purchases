<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\ServerNotifications;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Imdhemy\GooglePlay\ClientFactory;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\GooglePlay\DeveloperNotifications\SubscriptionNotification;
use Imdhemy\Purchases\Contracts\SubscriptionContract;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;
use Imdhemy\Purchases\Tests\TestCase;

class GoogleServerNotificationTest extends TestCase
{
    private GoogleServerNotification $googleServerNotification;

    private DeveloperNotification $developerNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $data = 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwNDAwMjg0MjMzMiIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6MTMsInB1cmNoYXNlVG9rZW4iOiJuYWRpZmJwZWtmZmRjYmNvZGdwa2NrYWMuQU8tSjFPekxnU0ZQSWFESmVKTVF4dnZIYnBMeTlLZ3dYbHVyQjk1UlBINHFZdGYxSmdzV1B3NHV4bmlkYUlmeGJreXVpTDVOZ3ZudVU3TEpvNzIzeHpfVVRhUEZXc0YyZEEiLCJzdWJzY3JpcHRpb25JZCI6Im1vbnRoX3ByZW1pdW0ifX0=';
        $this->developerNotification = DeveloperNotification::parse($data);
        $this->googleServerNotification = new GoogleServerNotification($this->developerNotification);
    }

    /**
     * @test
     */
    public function get_type(): void
    {
        $this->assertEquals(
            SubscriptionNotification::SUBSCRIPTION_EXPIRED,
            $this->googleServerNotification->getType()
        );
    }

    /**
     * @test
     *
     * @throws GuzzleException
     */
    public function get_subscription(): void
    {
        $googleClient = ClientFactory::mock(new Response(200, [], '[]'));

        $subscription = $this->googleServerNotification->getSubscription($googleClient);

        $this->assertEquals(SubscriptionContract::PROVIDER_GOOGLE_PLAY, $subscription->getProvider());
    }

    /**
     * @test
     */
    public function get_bundle(): void
    {
        $this->assertEquals('com.twigano.fashion', $this->googleServerNotification->getBundle());
    }

    /**
     * @test
     */
    public function get_payload(): void
    {
        $this->assertEquals($this->developerNotification->toArray(), $this->googleServerNotification->getPayload());
    }

    /**
     * @test
     */
    public function get_provider(): void
    {
        $this->assertEquals('google_play', $this->googleServerNotification->getProvider());
    }
}
