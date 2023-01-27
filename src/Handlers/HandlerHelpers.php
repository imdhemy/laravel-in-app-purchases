<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Imdhemy\Purchases\Contracts\UrlGenerator;
use Imdhemy\Purchases\Events\EventFactory;

/**
 * Handler Helpers
 * This class is used to provide common services to the handlers.
 */
final class HandlerHelpers implements HandlerHelpersInterface
{
    private Request $request;
    private Factory $validator;
    private UrlGenerator $urlGenerator;
    private EventFactory $eventFactory;

    public function __construct(
        Request $request,
        Factory $validator,
        UrlGenerator $urlGenerator,
        EventFactory $eventFactory
    ) {
        $this->request = $request;
        $this->validator = $validator;
        $this->urlGenerator = $urlGenerator;
        $this->eventFactory = $eventFactory;
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

    public function getEventFactory(): EventFactory
    {
        return $this->eventFactory;
    }
}
