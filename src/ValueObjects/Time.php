<?php


namespace Imdhemy\Purchases\ValueObjects;

use Carbon\Carbon;
use DateTime;
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
        $obj = new self(0);
        $obj->carbon = $time->getCarbon();

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
