<?php

namespace Tests\Console;

use Tests\TestCase;

class LiapUrlCommandTest extends TestCase
{
    /**
     * @test
     */
    public function signature()
    {
        $this->artisan('liap:url')->assertSuccessful();
    }
}
