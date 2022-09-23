<?php

namespace Imdhemy\Purchases\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;
use Imdhemy\Purchases\Contracts\UrlGenerator;

abstract class AbstractNotificationHandler implements NotificationHandlerContract
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Factory
     */
    protected Factory $validator;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @param HandlerHelpersInterface $helpers
     */
    public function __construct(HandlerHelpersInterface $helpers)
    {
        $this->request = $helpers->getRequest();
        $this->validator = $helpers->getValidator();
        $this->urlGenerator = $helpers->getUrlGenerator();
    }

    /**
     * Executes the handler
     *
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function execute()
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

    /**
     * @return bool
     */
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

    /**
     * @return mixed
     */
    abstract protected function handle();
}
