<?php


namespace Imdhemy\Purchases\GooglePlay\Subscriptions;

class Response
{

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        return new self();
    }
}
