<?php

namespace Imdhemy\Purchases\Tests\Events;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;
use Imdhemy\Purchases\Events\PurchaseEvent;
use Imdhemy\Purchases\ServerNotifications\GoogleServerNotification;
use Imdhemy\Purchases\Tests\TestCase;

class PurchaseEventTest extends TestCase
{
    /**
     * @var PurchaseEvent
     */
    private $event;

    /**
     * @var GoogleServerNotification
     */
    private $googleServerNotification;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $data = 'eyJ2ZXJzaW9uIjoiMS4wIiwicGFja2FnZU5hbWUiOiJjb20udHdpZ2Fuby5mYXNoaW9uIiwiZXZlbnRUaW1lTWlsbGlzIjoiMTYwMzMwMDgwNzM2MSIsInN1YnNjcmlwdGlvbk5vdGlmaWNhdGlvbiI6eyJ2ZXJzaW9uIjoiMS4wIiwibm90aWZpY2F0aW9uVHlwZSI6NCwicHVyY2hhc2VUb2tlbiI6ImFuZWZjcG1jamZib2RqbGNqZWVhY2piaC5BTy1KMU95NkxWQ2lJSkJBWUY4WVJCZklsaGZiSjlWTUJUamUybHo1bk1vSUV1SEdpMmdLVHczQXlZWEN4enhueGxKbWNOb0NEZlo2VnhFR05EQ0lLS1ZuVXZqUFZRODBPZyIsInN1YnNjcmlwdGlvbklkIjoid2Vla19wcmVtaXVtIn19';
        $developerNotification = DeveloperNotification::parse($data);
        $this->googleServerNotification = new GoogleServerNotification($developerNotification);
        $this->event = new SubscriptionRenewed($this->googleServerNotification);
    }

    /**
     * @test
     */
    public function test_it_can_get_server_notification()
    {
        $this->assertSame(
            $this->googleServerNotification,
            $this->event->getServerNotification()
        );
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_it_can_get_subscription()
    {
        $this->assertEquals(
            $this->googleServerNotification->getSubscription(),
            $this->event->getSubscription()
        );
    }
}
