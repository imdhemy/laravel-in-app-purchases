<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as IlluminateUrlGenerator;
use Imdhemy\Purchases\Console\UrlGenerator;
use Imdhemy\Purchases\Tests\TestCase;

final class UrlGeneratorTest extends TestCase
{
    /** @test */
    public function create_signed_url(): string
    {
        $internalGenerator = $this->createMock(IlluminateUrlGenerator::class);
        $sut = new UrlGenerator($internalGenerator, $this->app);

        $internalGenerator->expects($this->once())
            ->method('signedRoute')
            ->with('liap.serverNotifications')
            ->willReturn('https://example.com?signature=signature');

        return $sut->signedUrl('my-provider');
    }

    /**
     * @test
     *
     * @depends create_signed_url
     */
    public function signed_url_contains_the_provider_query(string $url): void
    {
        $this->assertStringContainsString('provider=my-provider', $url);
    }

    /** @test */
    public function validate_secure_url(): void
    {
        $internalGenerator = $this->app->make(IlluminateUrlGenerator::class);
        $sut = new UrlGenerator($internalGenerator, $this->app);
        $url = $sut->signedUrl('my-provider');

        /** @var array{query: string, host: string, path: string} $urlParts */
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);
        $request = new Request($query, [], [], [], [], [
            'QUERY_STRING' => $urlParts['query'],
            'HTTP_HOST' => $urlParts['host'],
            'REQUEST_URI' => $urlParts['path'],
        ]);
        $this->assertTrue($sut->hasValidSignature($request));
    }

    /** @test */
    public function create_unsigned_url(): void
    {
        $internalGenerator = $this->createMock(IlluminateUrlGenerator::class);
        $app = $this->createMock(Application::class);
        $sut = new UrlGenerator($internalGenerator, $app);

        $internalGenerator->expects($this->once())->method('route')->with('liap.serverNotifications');

        $sut->unsignedUrl('my-provider');
    }

    /** @test */
    public function has_valid_signature_delegates_call_to_laravel_9_implementation(): void
    {
        $app = $this->createMock(Application::class);
        $app->expects($this->once())->method('version')->willReturn('9.0.0');
        $internalGenerator = $this->createMock(IlluminateUrlGenerator::class);
        $sut = new UrlGenerator($internalGenerator, $app);
        $request = new Request();

        $internalGenerator->expects($this->once())->method('hasValidSignature')->with($request)->willReturn(true);

        $sut->hasValidSignature($request);
    }
}
