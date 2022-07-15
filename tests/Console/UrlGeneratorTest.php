<?php

namespace Tests\Console;

use Illuminate\Http\Request;
use Imdhemy\Purchases\Console\UrlGenerator;
use Mockery;
use Orchestra\Testbench\Foundation\PackageManifest;
use Tests\Doubles\Laravel9\Application;
use Tests\TestCase;

class UrlGeneratorTest extends TestCase
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->make(UrlGenerator::class);
    }

    /**
     * @test
     */
    public function generate_should_generate_a_signed_url()
    {
        $url = $this->urlGenerator->generate('my-provider');

        $this->assertStringContainsString('signature=', $url);
    }

    /**
     * @test
     */
    public function generate_should_add_the_provider_to_the_query()
    {
        $url = $this->urlGenerator->generate('my-provider');

        $this->assertStringContainsString('provider=my-provider', $url);
    }

    /**
     * @test
     */
    public function has_valid_signature_returns_true_with_valid_requests()
    {
        $url = $this->urlGenerator->generate('my-provider');
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);

        $request = new Request($query, [], [], [], [], [
          'QUERY_STRING' => $urlParts['query'],
          'HTTP_HOST' => $urlParts['host'],
          'REQUEST_URI' => $urlParts['path'],
        ]);

        $this->assertTrue($this->urlGenerator->hasValidSignature($request));
    }

    /**
     * @test
     */
    public function has_valid_signature_returns_false_with_modified_url()
    {
        $url = $this->urlGenerator->generate('my-provider') . "&attack=true";
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);

        $request = new Request($query, [], [], [], [], [
          'QUERY_STRING' => $urlParts['query'],
          'HTTP_HOST' => $urlParts['host'],
          'REQUEST_URI' => $urlParts['path'],
        ]);

        $this->assertFalse($this->urlGenerator->hasValidSignature($request));
    }

    /**
     * @test
     * @psalm-suppress UndefinedMagicMethod
     */
    public function has_valid_signature_delegates_call_laravel_9_implementation()
    {
        $this->expectNotToPerformAssertions();
        $this->app->setCustomVersion('9.0.0');

        /** @var \Illuminate\Routing\UrlGenerator|Mockery\MockInterface $mock */
        $mock = Mockery::mock(\Illuminate\Routing\UrlGenerator::class);
        $urlGenerator = new UrlGenerator($mock);
        $request = new Request();

        $mock->shouldReceive('hasValidSignature')
          ->once()
          ->with($request, true, ['provider']);

        $urlGenerator->hasValidSignature($request);

        Mockery::close();
    }

    /**
     * @inheritDoc
     */
    protected function resolveApplication()
    {
        return tap(new Application($this->getBasePath()), function ($app) {
            $app->bind(
                'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                'Orchestra\Testbench\Bootstrap\LoadConfiguration'
            );

            PackageManifest::swap($app, $this);
        });
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->app->setCustomVersion(\Illuminate\Foundation\Application::VERSION);

        parent::tearDown();
    }
}
