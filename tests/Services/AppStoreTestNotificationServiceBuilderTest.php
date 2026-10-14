<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Services;

use Imdhemy\Purchases\Services\AppStoreTestNotificationServiceBuilder;
use Imdhemy\Purchases\Tests\TestCase;
use RuntimeException;

class AppStoreTestNotificationServiceBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_when_the_private_key_file_is_not_readable(): void
    {
        $path = sys_get_temp_dir().'/liap-missing-private-key.p8';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read the App Store private key file "%s"', $path));

        (new AppStoreTestNotificationServiceBuilder())->of([
            'appstore_private_key_id' => 'key-id',
            'appstore_private_key' => $path,
            'appstore_issuer_id' => 'issuer-id',
            'appstore_bundle_id' => 'com.example.app',
            'sandbox' => true,
        ]);
    }
}
