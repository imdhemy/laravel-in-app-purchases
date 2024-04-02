<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Console;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Imdhemy\Purchases\Console\LiapUrlCommand;
use Imdhemy\Purchases\Contracts\UrlGenerator as UrlGeneratorContract;
use Imdhemy\Purchases\Tests\Doubles\UrlGenerator as FakeUrlGenerator;
use Imdhemy\Purchases\Tests\TestCase;

class LiapUrlCommandTest extends TestCase
{
    private const CHOICES = [
        LiapUrlCommand::PROVIDER_ALL,
        LiapUrlCommand::PROVIDER_APP_STORE,
        LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
    ];

    private const ALL_PROVIDERS = [
        LiapUrlCommand::PROVIDER_APP_STORE,
        LiapUrlCommand::PROVIDER_GOOGLE_PLAY,
    ];

    private UrlGeneratorContract $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('liap.routing.signed', true);

        $this->urlGenerator = $this->app->make(FakeUrlGenerator::class);
    }

    /**
     * A helper method to run the SUT.
     */
    private function runWithChoice(string $choice = LiapUrlCommand::PROVIDER_ALL): PendingCommand
    {
        return
            $this->artisan('liap:url')
                ->expectsChoice(LiapUrlCommand::CHOICE_PROVIDER, $choice, self::CHOICES);
    }

    /**
     * @test
     */
    public function it_can_generate_a_signed_url_for_a_single_provider(): void
    {
        $provider = $this->getRandomProvider();
        $expected = $this->getExpectedCollection([$provider]);

        $this->runWithChoice($provider)
            ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected)
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_can_generate_signed_routes_for_all_providers(): void
    {
        $expected = $this->getExpectedCollection(self::ALL_PROVIDERS);

        $this->runWithChoice()
            ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected)
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_does_not_sign_the_url_if_the_signing_routes_is_disabled(): void
    {
        config()->set('liap.routing.signed', false);

        $providers = self::ALL_PROVIDERS;
        $expected = new Collection();

        foreach ($providers as $provider) {
            $expected->add([
                $provider,
                route('liap.serverNotifications').'?provider='.Str::of($provider)->slug(),
            ]);
        }

        $this->runWithChoice()
            ->expectsConfirmation(LiapUrlCommand::CONFIRM_GENERATE_SIGNED_ROUTES)
            ->assertSuccessful()
            ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected->toArray());
    }

    /**
     * @test
     */
    public function it_can_force_signed_routes(): void
    {
        config()->set('liap.routing.signed', false);

        $providers = self::ALL_PROVIDERS;
        $expected = $this->getExpectedCollection($providers);

        $this->runWithChoice()
            ->expectsConfirmation(LiapUrlCommand::CONFIRM_GENERATE_SIGNED_ROUTES, 'yes')
            ->expectsTable(LiapUrlCommand::TABLE_HEADERS, $expected)
            ->assertSuccessful();
    }

    /**
     * Picks a random provider from the available providers.
     */
    private function getRandomProvider(): string
    {
        return $this->faker->randomElement(self::ALL_PROVIDERS);
    }

    private function getExpectedCollection(array $providers): array
    {
        $expected = new Collection();

        foreach ($providers as $provider) {
            $expected->add([
                $provider,
                $this->urlGenerator->generate((string)Str::of($provider)->slug()),
            ]);
        }

        return $expected->toArray();
    }
}
