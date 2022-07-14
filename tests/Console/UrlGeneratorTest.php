<?php

namespace Tests\Console;

use Imdhemy\Purchases\Console\UrlGenerator;
use Tests\TestCase;

class UrlGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_a_signed_url()
    {
        $urlGenerator = $this->app->make(UrlGenerator::class);

        $url = $urlGenerator->generate('my-provider');

        $this->assertStringContainsString('signature=', $url);
    }

    /**
     * @test
     */
    public function it_should_add_the_provider_to_the_query()
    {
        $urlGenerator = $this->app->make(UrlGenerator::class);

        $url = $urlGenerator->generate('my-provider');

        $this->assertStringContainsString('provider=my-provider', $url);
    }
}
