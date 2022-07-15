<?php

namespace Tests\Doubles\Laravel9;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * @var string
     */
    private $customVersion = \Illuminate\Foundation\Application::VERSION;

    /**
     * @inheritDoc
     */
    public function version(): string
    {
        return $this->customVersion;
    }

    /**
     * @param string $customVersion
     *
     * @return Application
     */
    public function setCustomVersion(string $customVersion): Application
    {
        $this->customVersion = $customVersion;

        return $this;
    }
}
