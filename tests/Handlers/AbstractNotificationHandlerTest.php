<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Events\EventFactory;
use Imdhemy\Purchases\Handlers\AbstractNotificationHandler;
use Imdhemy\Purchases\Handlers\HandlerHelpers;
use Imdhemy\Purchases\Tests\TestCase;

class AbstractNotificationHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @throws AuthorizationException|ValidationException
     */
    public function it_should_not_authorize_unsigned_urls()
    {
        $this->expectException(AuthorizationException::class);

        $this->app['config']->set('liap.routing.signed', true);

        $request = new Request();
        $validatorFactory = $this->app->make(Factory::class);
        $urlGenerator = $this->app->make(UrlGenerator::class);
        $eventFactory = new EventFactory();

        $stub = $this->getMockBuilder(AbstractNotificationHandler::class)
            ->setConstructorArgs([new HandlerHelpers($request, $validatorFactory, $urlGenerator, $eventFactory)])
            ->getMockForAbstractClass();

        $stub->execute();
    }

    /**
     * @test
     *
     * @throws AuthorizationException|ValidationException
     */
    public function it_should_authorized_a_signed_url_with_provider()
    {
        $this->expectNotToPerformAssertions();

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->app->make(UrlGenerator::class);
        $signedUrl = $urlGenerator->generate('app-store');

        $urlParts = parse_url($signedUrl);
        $queryString = $urlParts['query'];
        parse_str($queryString, $query);

        $this->app['config']->set('liap.routing.signed', true);

        $request = new Request(
            $query,
            [],
            [],
            [],
            [],
            [
                'REQUEST_URI' => $signedUrl,
                'QUERY_STRING' => $queryString,
                'HTTP_HOST' => 'localhost',
            ]
        );

        $request->attributes->set('signature', 'bar');
        $validatorFactory = $this->app->make(Factory::class);
        $eventFactory = new EventFactory();

        $stub = $this->getMockBuilder(AbstractNotificationHandler::class)
            ->setConstructorArgs([new HandlerHelpers($request, $validatorFactory, $urlGenerator, $eventFactory)])
            ->getMockForAbstractClass();

        $stub->execute();
    }
}
