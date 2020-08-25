<?php


namespace Imdhemy\Purchases\GooglePlay\Contracts;


interface ResponseInterface
{
    /**
     * @param string $token
     */
    public function setPurchaseToken(string $token): void ;
}
