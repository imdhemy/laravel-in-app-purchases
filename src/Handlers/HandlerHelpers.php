<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Contracts\UrlGenerator;

/**
 * Handler Helpers
 * This class is used to provide common services to the handlers
 */
final class HandlerHelpers implements HandlerHelpersInterface
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Factory
     */
    private Factory $validator;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @param Request $request
     * @param Factory $validator
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Request $request, Factory $validator, UrlGenerator $urlGenerator)
    {
        $this->request = $request;
        $this->validator = $validator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Factory
     */
    public function getValidator(): Factory
    {
        return $this->validator;
    }

    /**
     * @return UrlGenerator
     */
    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}
