<?php

namespace Imdhemy\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AppStoreServerNotificationRequest
 * @package Imdhemy\Purchases\Http\Requests
 */
class AppStoreServerNotificationRequest extends FormRequest
{
    /**
     * Validates the request body
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'unified_receipt' => ['array', 'required'],
            'unified_receipt.latest_receipt' => ['required'],
            'unified_receipt.latest_receipt_info' => ['required', 'array'],
            'notification_type' => ['required'],
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
