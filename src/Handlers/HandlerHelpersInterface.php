<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * Handler Helpers
 * This class is used to provide common services to the handlers.
 */
interface HandlerHelpersInterface
{
    public function getRequest(): Request;

    public function getValidator(): Factory;

    public function getUrlGenerator(): UrlGenerator;
}
