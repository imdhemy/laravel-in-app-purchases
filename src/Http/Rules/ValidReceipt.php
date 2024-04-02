<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Http\Rules;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\Verifier;

/**
 * @deprecated - This rule will be removed in the next major release
 */
class ValidReceipt implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @throws InvalidReceiptException
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArgument
     * @psalm-suppress DeprecatedMethod
     *
     * @deprecated     - This rule will be removed in the next major release
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
     */
    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
