<?php

declare(strict_types=1);

namespace Imdhemy\Purchases\ValueObjects;

use Carbon\Carbon;
use DateTime;
use Imdhemy\AppStore\ValueObjects\Time as AppStoreTime;
use Imdhemy\GooglePlay\ValueObjects\Time as GoogleTime;
use Stringable;

/**
 * Class Time
 * A smart value object for time.
 */
final class Time implements Stringable
{
    /**
     * @var int the number of microseconds since the Unix epoch
     */
    private int $timestampMilliseconds;

    /**
     * Time constructor.
     */
    public function __construct(int $timestampMilliseconds)
    {
        $this->timestampMilliseconds = $timestampMilliseconds;
    }

    /**
     * @return static
     */
    public static function fromGoogleTime(GoogleTime $time): self
    {
        return self::fromCarbon($time->getCarbon());
    }

    /**
     * @return static
     */
    public static function fromAppStoreTime(AppStoreTime $time): self
    {
        return self::fromCarbon($time->toCarbon());
    }

    /**
     * @return static
     */
    public static function fromCarbon(Carbon $carbon): self
    {
        return new self($carbon->getTimestampMs());
    }

    /**
     * @return static
     */
    public static function fromDateTime(DateTime $dateTime): self
    {
        return self::fromCarbon(Carbon::instance($dateTime));
    }

    public function isFuture(): bool
    {
        return $this->toCarbon()->isFuture();
    }

    public function isPast(): bool
    {
        return $this->toCarbon()->isPast();
    }

    /**
     * @deprecated use toCarbon() instead
     */
    public function getCarbon(): Carbon
    {
        return $this->toCarbon();
    }

    /**
     * Converts the value object to a Carbon instance.
     */
    public function toCarbon(): Carbon
    {
        return Carbon::createFromTimestampMs($this->timestampMilliseconds);
    }

    /**
     * Convert the value object to a DateTime instance.
     */
    public function toDateTime(): DateTime
    {
        return $this->toCarbon()->toDateTime();
    }

    public function __toString(): string
    {
        return (string)$this->toCarbon();
    }
}
