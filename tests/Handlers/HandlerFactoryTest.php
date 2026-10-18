<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Handlers;

use Illuminate\Http\Request;
use Imdhemy\Purchases\Handlers\GooglePlayNotificationHandler;
use Imdhemy\Purchases\Handlers\HandlerFactory;
use Imdhemy\Purchases\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class HandlerFactoryTest extends TestCase
{
    #[Test]
    public function it_rejects_an_array_valued_provider(): void
    {
        $request = Request::create('/liap/notifications?provider[]=app-store');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid provider: expected a string.');

        (new HandlerFactory($this->app, $request))->create();
    }

    #[Test]
    public function it_selects_the_provider_from_the_query_string(): void
    {
        $request = Request::create('/liap/notifications?provider=google-play', 'POST', [
            'provider' => 'app-store',
        ]);
        $request->attributes->set('provider', 'app-store');

        $handler = (new HandlerFactory($this->app, $request))->create();

        $this->assertInstanceOf(GooglePlayNotificationHandler::class, $handler);
    }
}
