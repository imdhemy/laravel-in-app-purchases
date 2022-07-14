<?php

namespace Tests\Doubles;

use Imdhemy\Purchases\Console\UrlGenerator;

/**
 * A Double class to allow testing signed URLs
 */
class UrlGeneratorDouble extends UrlGenerator
{
    /**
     * @inheritDoc
     */
    public function generate(string $provider): string
    {
        return sprintf("https://example.com?signature=fake_signature&provider=%s", $provider);
    }
}
