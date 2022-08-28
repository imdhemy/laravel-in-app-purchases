<?php

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
     * @var int The number of microseconds since the Unix epoch.
     */
    private int $timestampMilliseconds;

    /**
     * Time constructor
     *
     * @param int $timestampMilliseconds
     */
    public function __construct(int $timestampMilliseconds)
    {
        $this->timestampMilliseconds = $timestampMilliseconds;
    }

    /**
     * @param GoogleTime $time
     *
     * @return static
     */
    public static function fromGoogleTime(GoogleTime $time): self
    {
        return self::fromCarbon($time->getCarbon());
    }

    /**
     * @param AppStoreTime $time
     *
     * @return static
     */
    public static function fromAppStoreTime(AppStoreTime $time): self
    {
        return self::fromCarbon($time->getCarbon());
    }

    /**
     * @param Carbon $carbon
     *
     * @return static
     */
    public static function fromCarbon(Carbon $carbon): self
    {
        return new self($carbon->getTimestampMs());
    }

    /**
     * @param DateTime $dateTime
     *
     * @return static
     */
    public static function fromDateTime(DateTime $dateTime): self
    {
        return self::fromCarbon(Carbon::instance($dateTime));
    }

    /**
     * @return bool
     */
    public function isFuture(): bool
    {
        return $this->toCarbon()->isFuture();
    }

    /**
     * @return bool
     */
    public function isPast(): bool
    {
        return $this->toCarbon()->isPast();
    }

    /**
     * @return Carbon
     * @deprecated Use toCarbon() instead.
     */
    public function getCarbon(): Carbon
    {
        return $this->toCarbon();
    }

    /**
     * Converts the value object to a Carbon instance.
     *
     * @return Carbon
     */
    public function toCarbon(): Carbon
    {
        return Carbon::createFromTimestampMs($this->timestampMilliseconds);
    }

    /**
     * Convert the value object to a DateTime instance.
     *
     * @return DateTime
     */
    public function toDateTime(): DateTime
    {
        return $this->toCarbon()->toDateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->toCarbon();
    }
}
