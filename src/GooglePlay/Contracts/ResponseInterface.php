<?php


namespace Imdhemy\Purchases\GooglePlay\Contracts;

interface ResponseInterface
{
    /**
     * @param string $token
     */
    public function setPurchaseToken(string $token): void;

    /**
     * @return string
     */
    public function getPlatform(): string;

    /**
     * @return string
     */
    public function getKind(): string;

    /**
     * @return string
     */
    public function getItemId(): string;
}
