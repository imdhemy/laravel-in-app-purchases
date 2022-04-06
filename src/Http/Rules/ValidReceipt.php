<?php

namespace Imdhemy\Purchases\Http\Rules;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\Verifier;

class ValidReceipt implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws InvalidReceiptException
     */
    public function passes($attribute, $value): bool
    {
        $receiptData = $value['latest_receipt'];
        $password = config('purchase.appstore_password');
        $sandbox = (bool)config('purchase.appstore_sandbox');
        $client = ClientFactory::create($sandbox);
        $verifier = new Verifier($client, $receiptData, $password);

        try {
            $response = $verifier->verifyRenewable();

            return $response->getStatus()->isValid();
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The :attribute is invalid.";
    }
}
