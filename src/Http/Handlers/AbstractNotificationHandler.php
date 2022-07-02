<?php

namespace Imdhemy\Purchases\Http\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Imdhemy\Purchases\Contracts\NotificationHandlerContract;

abstract class AbstractNotificationHandler implements NotificationHandlerContract
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * @param Request $request
     * @param Factory $validator
     */
    public function __construct(Request $request, Factory $validator)
    {
        $this->request = $request;
        $this->validator = $validator;
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
    protected function authorize()
    {
        if (!$this->isAuthorized()) {
            throw new  AuthorizationException();
        }
    }

    /**
     * @return bool
     */
    abstract protected function isAuthorized(): bool;

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
    abstract protected function rules(): array;

    /**
     * @return mixed
     */
    abstract protected function handle();
}
