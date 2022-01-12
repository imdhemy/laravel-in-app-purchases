<?php

namespace Imdhemy\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiStoreNotificationRequest extends FormRequest
{
    /**
     * Validates the request body
     *
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }

    /**
     * Authorizes the request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
