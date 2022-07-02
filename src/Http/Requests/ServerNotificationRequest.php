<?php

namespace Imdhemy\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base server notification request
 */
abstract class ServerNotificationRequest extends FormRequest
{
    /**
     * @return array
     */
    abstract public function rules(): array;

    /**
     * @return bool
     */
    abstract public function authorize(): bool;
}
