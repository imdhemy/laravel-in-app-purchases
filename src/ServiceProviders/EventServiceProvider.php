<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

/**
 * Event Service Provider
 * Binds the events of the subscription life-cycle to the configured event handlers.
 */
class EventServiceProvider extends BaseEventServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->listen = (array)config(LiapServiceProvider::CONFIG_KEY.'.eventListeners');
    }
}
