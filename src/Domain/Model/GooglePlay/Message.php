<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Domain\Model\GooglePlay;

final readonly class Message
{
    public function __construct(
        public string $data,
    ) {
    }
}
