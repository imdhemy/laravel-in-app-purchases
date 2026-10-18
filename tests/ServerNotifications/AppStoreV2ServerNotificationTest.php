<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\ServerNotifications;

use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AppStoreV2ServerNotificationTest extends TestCase
{
    private V2DecodedPayload $payload;

    private AppStoreV2ServerNotification $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $claims = [
            'notificationType' => V2DecodedPayload::TYPE_SUBSCRIBED,
            'subtype' => V2DecodedPayload::SUBTYPE_INITIAL_BUY,
            'data' => [
                'bundleId' => 'com.example.app',
            ],
        ];

        $this->payload = V2DecodedPayload::fromArray($claims);

        $this->sut = AppStoreV2ServerNotification::fromDecodedPayload($this->payload);
    }

    #[Test]
    public function get_type(): void
    {
        $this->assertEquals(V2DecodedPayload::TYPE_SUBSCRIBED, $this->sut->getType());
    }

    #[Test]
    public function get_subscription(): void
    {
        $this->assertSame(
            $this->payload,
            $this->sut->getSubscription()->getProviderRepresentation()
        );
    }

    #[Test]
    public function is_test(): void
    {
        $this->assertFalse($this->sut->isTest());
    }

    #[Test]
    public function get_bundle(): void
    {
        $this->assertEquals('com.example.app', $this->sut->getBundle());
    }

    #[Test]
    public function get_payload(): void
    {
        $this->assertSame($this->payload->toArray(), $this->sut->getPayload());
    }

    #[Test]
    public function get_subtype(): void
    {
        $this->assertEquals(V2DecodedPayload::SUBTYPE_INITIAL_BUY, $this->sut->getSubtype());
    }

    #[Test]
    public function get_provider(): void
    {
        $this->assertEquals('app_store', $this->sut->getProvider());
    }
}
