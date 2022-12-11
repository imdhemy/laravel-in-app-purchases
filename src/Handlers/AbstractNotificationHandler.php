<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use Imdhemy\Purchases\Contracts\UrlGenerator;

abstract class AbstractNotificationHandler implements NotificationHandlerContract
{
    protected Request $request;

    protected Factory $validator;

    private UrlGenerator $urlGenerator;

    public function __construct(HandlerHelpersInterface $helpers)
    {
        $this->request = $helpers->getRequest();
        $this->validator = $helpers->getValidator();
        $this->urlGenerator = $helpers->getUrlGenerator();
    }

    /**
     * Executes the handler.
     *
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function execute(): void
    {
        $this->authorize();

        $this->validate();

        $this->handle();
    }

    /**
     * @throws AuthorizationException
     */
    protected function authorize(): void
    {
        if (! $this->isAuthorized()) {
            throw new AuthorizationException();
        }
    }

    protected function isAuthorized(): bool
    {
        $shouldAuthorize = (bool)config('liap.routing.signed', false);

        if (! $shouldAuthorize) {
            return true;
        }

        return $this->urlGenerator->hasValidSignature($this->request);
    }

    /**
     * @throws ValidationException
     */
    protected function validate(): void
    {
        $this->validator->make($this->request->all(), $this->rules())->validate();
    }

    /**
     * @return string[][]
     */
    protected function rules(): array
    {
        return [];
    }

    abstract protected function handle(): void;
}
