<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Doubles;

use Imdhemy\Purchases\Console\UrlGenerator as BaseUrlGenerator;

/**
 * A Double class to allow testing signed URLs.
 */
class UrlGenerator extends BaseUrlGenerator
{
    public function generate(string $provider): string
    {
        return sprintf('https://example.com?signature=fake_signature&provider=%s', $provider);
    }
}
