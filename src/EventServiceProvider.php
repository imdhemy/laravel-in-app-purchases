<?php


namespace Imdhemy\Purchases;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

/**
 * Class EventServiceProvider
 * @package Imdhemy\Purchases
 */
class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [];

    /**
     * EventServiceProvider constructor.
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->listen = config('purchase.eventListeners');
    }

    /**
     * Register any events for your application
     */
    public function boot()
    {
        parent::boot();
    }
}
