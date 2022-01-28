<?php

namespace Imdhemy\Purchases\Http\Requests;

use CHfur\AppGallery\Exceptions\InvalidPublicKeyException;
use CHfur\AppGallery\Validation\SignatureVerifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'statusUpdateNotification' => ['required', 'string'],
            'notifycationSignature' => ['required', 'string'],
            'signatureAlgorithm' => ['required', 'string', Rule::in(['SHA256WithRSA/PSS', 'SHA256WithRSA'])]
        ];
    }

    /**
     * Authorizes the request
     *
     * @return bool
     * @throws InvalidPublicKeyException
     */
    public function authorize(): bool
    {
        $signatureVerifier = new SignatureVerifier(config('purchase.app_gallery_public_key'));
        return $signatureVerifier->verify(
            $this->statusUpdateNotification,
            $this->notifycationSignature,
            $this->signatureAlgorithm
        );
    }
}
