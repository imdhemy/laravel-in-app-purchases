<?php

namespace Tests\Unit\ValueObjects;

use Imdhemy\Purchases\ValueObjects\Time;
use Stringable;
use Tests\TestCase;

class TimeTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_stringable(): void
    {
        $sut = new Time(0);
        $this->assertInstanceOf(Stringable::class, $sut);
        $this->assertEquals('1970-01-01 00:00:00', $sut->__toString());
    }
}
