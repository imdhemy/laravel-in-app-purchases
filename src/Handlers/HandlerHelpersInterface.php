<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * Handler Helpers
 * This class is used to provide common services to the handlers
 */
interface HandlerHelpersInterface
{
    /**
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * @return Factory
     */
    public function getValidator(): Factory;

    /**
     * @return UrlGenerator
     */
    public function getUrlGenerator(): UrlGenerator;
}
