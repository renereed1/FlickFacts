<?php declare(strict_types=1);

namespace FlickFacts\Common\Infrastructure\Clock;

use DateMalformedStringException;
use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;

class SystemClock implements Clock
{

    /**
     * Returns the current date and time or a specified point in time.
     *
     * @param string $when A string representing a date/time (default is 'now').
     *                     Can be any valid string format accepted by DateTimeImmutable.
     *
     * @return DateTimeImmutable An immutable DateTime object representing the specified time.
     *
     * @throws DateMalformedStringException If the provided string is malformed or invalid.
     */
    public function now(string $when = 'now'): DateTimeImmutable
    {
        return new DateTimeImmutable($when);
    }
}