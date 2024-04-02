<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\Doubles\Laravel9;

use Illuminate\Foundation\Application as IlluminateApplication;

/**
 * Application.
 *
 * This is a dummy application for testing purposes.
 * It allows to test against a custom semantic version of the application.
 */
class Application extends IlluminateApplication
{
    private string $customVersion = IlluminateApplication::VERSION;

    /**
     * @const string The original laravel version installed by orchestra
     */
    public const ORIGINAL_VERSION = IlluminateApplication::VERSION;

    public function version(): string
    {
        return $this->customVersion;
    }

    /**
     * Sets the custom version of the application.
     */
    public function setCustomVersion(string $customVersion): Application
    {
        $this->customVersion = $customVersion;

        return $this;
    }
}
