<?php

namespace Imdhemy\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServerNotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getProvider(): string
    {
        return (string)$this->get('provider');
    }
}
