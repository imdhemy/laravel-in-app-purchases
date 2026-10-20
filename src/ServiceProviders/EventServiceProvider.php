<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Override;

/**
 * Registers application listeners configured through liap.eventListeners.
 *
 * @internal package infrastructure; not part of the public API
 */
final class EventServiceProvider extends BaseEventServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        /** @var array<string, array<int, string>> $eventListeners */
        $eventListeners = (array)config(LiapServiceProvider::CONFIG_KEY.'.eventListeners');
        $this->listen = $eventListeners;
    }

    #[Override]
    protected function configureEmailVerification(): void
    {
    }
}
