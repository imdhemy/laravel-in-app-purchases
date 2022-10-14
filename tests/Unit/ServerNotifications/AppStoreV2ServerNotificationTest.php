<?php

namespace Tests\Unit\ServerNotifications;

use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\Purchases\ServerNotifications\AppStoreV2ServerNotification;
use Tests\TestCase;

class AppStoreV2ServerNotificationTest extends TestCase
{
    private V2DecodedPayload $payload;

    private AppStoreV2ServerNotification $sut;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $claims = [
            'notificationType' => V2DecodedPayload::TYPE_TEST,
            'data' => [
                'bundleId' => 'com.example.app',
            ],
        ];

        $this->payload = V2DecodedPayload::fromArray($claims);

        $this->sut = AppStoreV2ServerNotification::fromDecodedPayload($this->payload);
    }

    /**
     * @test
     */
    public function get_type(): void
    {
        $this->assertEquals(V2DecodedPayload::TYPE_TEST, $this->sut->getType());
    }

    /**
     * @test
     */
    public function get_subscription(): void
    {
        $this->assertSame(
            $this->payload,
            $this->sut->getSubscription()->getProviderRepresentation()
        );
    }

    /**
     * @test
     */
    public function is_test(): void
    {
        $this->assertTrue($this->sut->isTest());
    }

    /**
     * @test
     */
    public function get_bundle(): void
    {
        $this->assertEquals('com.example.app', $this->sut->getBundle());
    }

    /**
     * @test
     */
    public function get_payload(): void
    {
        $this->assertSame($this->payload->toArray(), $this->sut->getPayload());
    }
}
