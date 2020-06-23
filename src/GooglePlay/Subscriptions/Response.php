<?php


namespace Imdhemy\Purchases\GooglePlay\Subscriptions;

/**
 * Class Response
 * @package Imdhemy\Purchases\GooglePlay\Subscriptions
 */
class Response
{
    /**
     * @var int Possible values are 0. Yet to be acknowledged or 1. acknowledged
     */
    private $acknowledgementState;

    /**
     * @var bool Whether the subscription will automatically be renewed when it reaches its current expiry time
     */
    private $autoRenewing;

    /**
     * @var int Time at which the subscription will be automatically resumed
     */
    private $autoResumeTimeMillis;

    /**
     * @var int Possible values are 0. User canceled the subscription 1. Subscription was canceled by the system, for example because of a billing problem 2. Subscription was replaced with a new subscription 3.Subscription was canceled by the developer
     */
    private $cancelReason;

    /**
     * @var array Information provided by the user when they complete the subscription cancellation flow (cancellation reason survey).
     */
    private $cancelSurveyResult;

    /**
     * @var string ISO 3166-1 alpha-2 billing country/region code of the user at the time the subscription was granted.
     */
    private $countryCode;

    /**
     * @var string A developer-specified string that contains supplemental information about an order.
     */
    private $developerPayload;

    /**
     * @var string The email address of the user when the subscription was purchased. Only present for purchases made with 'Subscribe with Google'.
     */
    private $emailAddress;

    /**
     * @var int Time at which the subscription will expire, in milliseconds since the Epoch.
     */
    private $expiryTimeMillis;

    /**
     * @var string The family name of the user when the subscription was purchased. Only present for purchases made with 'Subscribe with Google'.
     */
    private $familyName;

    /**
     * @var string The given name of the user when the subscription was purchased. Only present for purchases made with 'Subscribe with Google'.
     */
    private $givenName;

    /**
     * @var array Introductory price information of the subscription. This is only present when the subscription was purchased with an introductory price.
     */
    private $introductoryPriceInfo;

    /**
     * @var string This kind represents a subscriptionPurchase object in the android publisher service.
     */
    private $kind;

    /**
     * @var string The purchase token of the originating purchase if this subscription.
     */
    private $linkedPurchaseToken;

    /**
     * @var string An obfuscated version of the id that is uniquely associated with the user's account in your app
     */
    private $obfuscatedExternalAccountId;

    /**
     * @var string An obfuscated version of the id that is uniquely associated with the user's profile in your app
     */
    private $obfuscatedExternalProfileId;

    /**
     * @var string The order id of the latest recurring order associated with the purchase of the subscription.
     */
    private $orderId;

    /**
     * @var int The payment state of the subscription. Possible values are: 0. Payment pending 1. Payment received 2. Free trial 3. Pending deferred upgrade/downgrade
     */
    private $paymentState;

    /**
     * @var int Price of the subscription, not including tax
     */
    private $priceAmountMicros;

    /**
     * @var array The latest price change information available,
     */
    private $priceChange;

    /**
     * @var string ISO 4217 currency code for the subscription price.
     */
    private $priceCurrencyCode;

    /**
     * @var string The Google profile id of the user when the subscription was purchased.
     */
    private $profileId;

    /**
     * @var string The profile name of the user when the subscription was purchased.
     */
    private $profileName;

    /**
     * @var string The promotion code applied on this purchase
     */
    private $promotionCode;

    /**
     * @var int The type of promotion applied on this purchase, 0. One time code 1. Vanity code
     */
    private $promotionType;

    /**
     * @var int 0. Test, 1. Promo
     */
    private $purchaseType;

    /**
     * @var int Time at which the subscription was granted, in milliseconds since the Epoch.
     */
    private $startTimeMillis;

    /**
     * @var int The time at which the subscription was canceled by the user, in milliseconds since the epoch. Only present if cancelReason is 0.
     */
    private $userCancellationTimeMillis;

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $response = new self();
        self::fillObjectVars($attributes, $response);
        return $response;
    }

    /**
     * @param array $attributes
     * @param Response $response
     */
    private static function fillObjectVars(array $attributes, Response $response): void
    {
        $objectVars = array_keys(get_object_vars($response));
        foreach ($attributes as $key => $value) {
            if (in_array($key, $objectVars)) {
                $response->$key = $value;
            }
        }
    }

    /**
     * @return int
     */
    public function getAcknowledgementState(): int
    {
        return $this->acknowledgementState;
    }

    /**
     * @return bool
     */
    public function isAutoRenewing(): bool
    {
        return $this->autoRenewing;
    }

    /**
     * @return int
     */
    public function getAutoResumeTimeMillis(): int
    {
        return $this->autoResumeTimeMillis;
    }

    /**
     * @return int
     */
    public function getCancelReason(): int
    {
        return $this->cancelReason;
    }

    /**
     * @return array
     */
    public function getCancelSurveyResult(): array
    {
        return $this->cancelSurveyResult;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getDeveloperPayload(): string
    {
        return $this->developerPayload;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return int
     */
    public function getExpiryTimeMillis(): int
    {
        return $this->expiryTimeMillis;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @return array
     */
    public function getIntroductoryPriceInfo(): array
    {
        return $this->introductoryPriceInfo;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @return string
     */
    public function getLinkedPurchaseToken(): string
    {
        return $this->linkedPurchaseToken;
    }

    /**
     * @return string
     */
    public function getObfuscatedExternalAccountId(): string
    {
        return $this->obfuscatedExternalAccountId;
    }

    /**
     * @return string
     */
    public function getObfuscatedExternalProfileId(): string
    {
        return $this->obfuscatedExternalProfileId;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return int
     */
    public function getPaymentState(): int
    {
        return $this->paymentState;
    }

    /**
     * @return int
     */
    public function getPriceAmountMicros(): int
    {
        return $this->priceAmountMicros;
    }

    /**
     * @return array
     */
    public function getPriceChange(): array
    {
        return $this->priceChange;
    }

    /**
     * @return string
     */
    public function getPriceCurrencyCode(): string
    {
        return $this->priceCurrencyCode;
    }

    /**
     * @return string
     */
    public function getProfileId(): string
    {
        return $this->profileId;
    }

    /**
     * @return string
     */
    public function getProfileName(): string
    {
        return $this->profileName;
    }

    /**
     * @return string
     */
    public function getPromotionCode(): string
    {
        return $this->promotionCode;
    }

    /**
     * @return int
     */
    public function getPromotionType(): int
    {
        return $this->promotionType;
    }

    /**
     * @return int
     */
    public function getPurchaseType(): int
    {
        return $this->purchaseType;
    }

    /**
     * @return int
     */
    public function getStartTimeMillis(): int
    {
        return $this->startTimeMillis;
    }

    /**
     * @return int
     */
    public function getUserCancellationTimeMillis(): int
    {
        return $this->userCancellationTimeMillis;
    }
}
