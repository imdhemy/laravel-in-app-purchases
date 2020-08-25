<?php


namespace Imdhemy\Purchases\GooglePlay\Product;

use Imdhemy\Purchases\GooglePlay\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $purchaseTimeMillis;

    /**
     * @var int
     */
    private $consumptionState;

    /**
     * @var string
     */
    private $developerPayload;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var int
     */
    private $purchaseType;

    /**
     * @var int
     */
    private $acknowledgementState;

    /**
     * @var string
     */
    private $purchaseToken;

    /**
     * @var string
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $obfuscatedExternalAccountId;

    /**
     * @var string
     */
    private $obfuscatedExternalProfileId;

    /**
     * @param array $properties
     * @return Response
     */
    public static function fromArray(array $properties): self
    {
        $response = new self();
        foreach ($properties as $property => $value) {
            if (property_exists($response, $property)) {
                $response->$property = $value;
            }
        }

        return $response;
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
    public function getPurchaseTimeMillis(): string
    {
        return $this->purchaseTimeMillis;
    }

    /**
     * @return int
     */
    public function getConsumptionState(): int
    {
        return $this->consumptionState;
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
    public function getOrderId(): string
    {
        return $this->orderId;
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
    public function getAcknowledgementState(): int
    {
        return $this->acknowledgementState;
    }

    /**
     * @return string
     */
    public function getPurchaseToken(): string
    {
        return $this->purchaseToken;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
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
     * @param string $purchaseToken
     */
    public function setPurchaseToken(string $purchaseToken): void
    {
        $this->purchaseToken = $purchaseToken;
    }
}
