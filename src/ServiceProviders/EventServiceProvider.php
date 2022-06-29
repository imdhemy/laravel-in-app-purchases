<?php

namespace Imdhemy\Purchases\ServiceProviders;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

/**
 * Event Service Provider
 * Binds the events of the subscription life-cycle to the configured event handlers.
 */
class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [];

    /**
     * EventServiceProvider constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->listen = (array)config('purchase.eventListeners');
    }

    /**
     * Register any events for your application
     */
    public function boot()
    {
        parent::boot();
    }
}
