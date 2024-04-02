<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Foundation\Bootstrap\LoadConfiguration as IlluminateLoadConfiguration;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as IlluminateUrlGenerator;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Tests\Doubles\Laravel9\Application;
use Imdhemy\Purchases\Tests\TestCase;
use Mockery;
use Orchestra\Testbench\Bootstrap\LoadConfiguration as OrchestraLoadConfiguration;
use Orchestra\Testbench\Foundation\PackageManifest;

class UrlGeneratorTest extends TestCase
{
    private UrlGenerator $sut;

    /**
     * @var Application
     */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->app->make(UrlGenerator::class);
    }

    /**
     * @test
     */
    public function generate_should_create_a_url_with_signature_query(): string
    {
        $url = $this->sut->generate('my-provider');

        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringContainsString('signature=', $url);

        return $url;
    }

    /**
     * @test
     *
     * @depends generate_should_create_a_url_with_signature_query
     *
     * @param string $url The generated url
     */
    public function generate_should_add_the_provider_to_the_query(string $url): void
    {
        $this->assertStringContainsString('provider=my-provider', $url);
    }

    /**
     * @test
     *
     * @depends generate_should_create_a_url_with_signature_query
     *
     * @param string $url The generated url
     */
    public function has_valid_signature_returns_true_with_valid_requests(string $url): void
    {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);

        $request = new Request($query, [], [], [], [], [
            'QUERY_STRING' => $urlParts['query'],
            'HTTP_HOST' => $urlParts['host'],
            'REQUEST_URI' => $urlParts['path'],
        ]);

        $this->assertTrue($this->sut->hasValidSignature($request));
    }

    /**
     * @test
     *
     * @depends generate_should_create_a_url_with_signature_query
     *
     * @param string $url The generated url
     */
    public function has_valid_signature_returns_false_with_modified_url(string $url): void
    {
        $modifiedUrl = $url.'&attack=true';
        $urlParts = parse_url($modifiedUrl);
        parse_str($urlParts['query'], $query);

        $request = new Request($query, [], [], [], [], [
            'QUERY_STRING' => $urlParts['query'],
            'HTTP_HOST' => $urlParts['host'],
            'REQUEST_URI' => $urlParts['path'],
        ]);

        $this->assertFalse($this->sut->hasValidSignature($request));
    }

    /**
     * @test
     *
     * @psalm-suppress UndefinedMagicMethod
     */
    public function has_valid_signature_delegates_call_to_laravel_9_implementation(): void
    {
        $this->app->setCustomVersion('9.0.0');

        /** @var IlluminateUrlGenerator|Mockery\MockInterface $mock */
        $mock = Mockery::mock(IlluminateUrlGenerator::class);

        $sut = new UrlGenerator($mock);
        $request = new Request();

        $mock->shouldReceive('hasValidSignature')
            ->once()
            ->with($request, true, ['provider'])
            ->andReturn(true);

        $this->assertTrue($sut->hasValidSignature($request));

        Mockery::close();
    }

    protected function resolveApplication()
    {
        return tap(new Application($this->getBasePath()), function ($app) {
            $app->bind(IlluminateLoadConfiguration::class, OrchestraLoadConfiguration::class);

            PackageManifest::swap($app, $this);
        });
    }

    protected function tearDown(): void
    {
        $this->app->setCustomVersion(Application::ORIGINAL_VERSION);

        parent::tearDown();
    }
}
