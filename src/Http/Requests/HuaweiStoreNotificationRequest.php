<?php

namespace Imdhemy\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HuaweiStoreNotificationRequest extends FormRequest
{
    /**
     * Validates the request body
     * @see https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-notifications-about-subscription-events-0000001050706084
     * @return array
     */
    public function rules(): array
    {
        return [
            'statusUpdateNotification' => ['required', 'text'],
            'notifycationSignature' => ['required', 'text'],
            'signatureAlgorithm' => ['required', 'text']
        ];
    }

    //TODO: check notifycationSignature valid
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
