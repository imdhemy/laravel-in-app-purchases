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
final class HandlerHelpers implements HandlerHelpersInterface
{
    private Request $request;

    private Factory $validator;

    private UrlGenerator $urlGenerator;

    public function __construct(Request $request, Factory $validator, UrlGenerator $urlGenerator)
    {
        $this->request = $request;
        $this->validator = $validator;
        $this->urlGenerator = $urlGenerator;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getValidator(): Factory
    {
        return $this->validator;
    }

    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}
