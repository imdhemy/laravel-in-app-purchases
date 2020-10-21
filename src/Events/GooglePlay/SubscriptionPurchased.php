<?php


namespace Imdhemy\Purchases\Events\GooglePlay;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Queue\SerializesModels;
use Imdhemy\GooglePlay\DeveloperNotifications\DeveloperNotification;

/**
 * Class SubscriptionPurchased
 * @package Imdhemy\Purchases\Events\GooglePlay
 */
class SubscriptionPurchased
{
    use Dispatchable, SerializesModels;

    /**
     * @var DeveloperNotification
     */
    public $developerNotification;

    /**
     * @var FormRequest
     */
    public $request;

    /**
     * SubscriptionPurchased constructor.
     * @param DeveloperNotification $developerNotification
     * @param FormRequest $request
     */
    public function __construct(DeveloperNotification $developerNotification, FormRequest $request)
    {
        $this->developerNotification = $developerNotification;
        $this->request = $request;
    }
}
