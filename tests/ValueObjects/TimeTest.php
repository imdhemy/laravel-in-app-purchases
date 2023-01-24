<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\Tests\ValueObjects;

use Carbon\Carbon;
use DateTime;
use Imdhemy\AppStore\ValueObjects\Time as AppStoreTime;
use Imdhemy\GooglePlay\ValueObjects\Time as GoogleTime;
use Imdhemy\Purchases\Tests\TestCase;
use Imdhemy\Purchases\ValueObjects\Time;
use Stringable;

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

    /**
     * @test
     */
    public function it_could_be_created_from_google_time(): void
    {
        $sut = Time::fromGoogleTime(new GoogleTime(0));

        $this->assertEquals('1970-01-01 00:00:00', $sut);
    }

    /**
     * @test
     */
    public function it_could_be_created_from_apple_time(): void
    {
        $sut = Time::fromAppStoreTime(new AppStoreTime(0));

        $this->assertEquals('1970-01-01 00:00:00', $sut);
    }

    /**
     * @test
     */
    public function it_could_be_created_from_carbon(): void
    {
        $sut = Time::fromCarbon(new Carbon('1970-01-01 00:00:00'));

        $this->assertEquals('1970-01-01 00:00:00', $sut);
    }

    /**
     * @test
     */
    public function it_could_be_created_from_date_time(): void
    {
        $sut = Time::fromDateTime(new DateTime('1970-01-01 00:00:00'));

        $this->assertEquals('1970-01-01 00:00:00', $sut);
    }

    /**
     * @test
     */
    public function is_future_should_return_true_given_value_is_in_the_future(): Time
    {
        $sut = new Time(time() * 1000 + 5000);

        $this->assertTrue($sut->isFuture());

        return $sut;
    }

    /**
     * @test
     */
    public function is_future_should_return_false_give_value_is_passed(): Time
    {
        $sut = new Time(time() * 1000 - 5000);

        $this->assertFalse($sut->isFuture());

        return $sut;
    }

    /**
     * @test
     *
     * @depends is_future_should_return_true_given_value_is_in_the_future
     * @depends is_future_should_return_false_give_value_is_passed
     */
    public function is_past_should_work_properly_with_is_future(Time $future, Time $past): void
    {
        $this->assertFalse($future->isPast());

        $this->assertTrue($past->isPast());
    }

    /**
     * @test
     */
    public function it_could_be_converted_into_carbon_instance(): void
    {
        $sut = new Time(0);

        $carbon = $sut->toCarbon();

        $this->assertEquals((string)$sut, (string)$carbon);
    }

    /**
     * @test
     */
    public function it_could_be_converted_into_date_time_object(): void
    {
        $sut = new Time(0);

        $dateTime = $sut->toDateTime();

        $this->assertEquals($dateTime, $dateTime);
    }

    /**
     * @test
     */
    public function it_should_be_immutable(): void
    {
        $sut = new Time(0);
        $strBefore = (string)$sut;

        $carbon = $sut->toCarbon();
        $carbon->addDay();

        $this->assertEquals($strBefore, (string)$sut);
    }
}
