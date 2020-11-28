<?php


namespace Imdhemy\Purchases\ValueObjects;

use Carbon\Carbon;
use DateTime;
use Imdhemy\AppStore\ValueObjects\Time as AppStoreTime;
use Imdhemy\GooglePlay\ValueObjects\Time as GoogleTime;

final class Time
{
    /**
     * @var Carbon
     */
    private $carbon;

    /**
     * Time constructor
     *
     * @param int $timestampMs
     */
    public function __construct(int $timestampMs)
    {
        $this->carbon = Carbon::createFromTimestampMs($timestampMs);
    }

    /**
     * @param GoogleTime $time
     * @return static
     */
    public static function fromGoogleTime(GoogleTime $time): self
    {
        return self::fromCarbon($time->getCarbon());
    }

    /**
     * @param AppStoreTime $time
     * @return static
     */
    public static function fromAppStoreTime(AppStoreTime $time): self
    {
        return self::fromCarbon($time->getCarbon());
    }

    /**
     * @param Carbon $carbon
     * @return static
     */
    public static function fromCarbon(Carbon $carbon): self
    {
        $obj = new self(0);
        $obj->carbon = $carbon;

        return $obj;
    }

    /**
     * @return bool
     */
    public function isFuture(): bool
    {
        return Carbon::now()->lessThan($this->carbon);
    }

    /**
     * @return bool
     */
    public function isPast(): bool
    {
        return Carbon::now()->greaterThan($this->carbon);
    }

    /**
     * @return Carbon
     */
    public function getCarbon(): Carbon
    {
        return $this->carbon;
    }

    /**
     * @return DateTime
     */
    public function toDateTime(): DateTime
    {
        return $this->carbon->toDateTime();
    }
}
