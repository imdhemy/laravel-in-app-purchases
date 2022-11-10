<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Exceptions;

class InvalidNotificationTypeException extends LiapException
{
    /**
     * @param string $required
     * @param string $provided
     *
     * @return InvalidNotificationTypeException
     */
    public static function create(string $required, string $provided): self
    {
        return new self("Invalid notification type. Required: $required but $provided provided");
    }
}
