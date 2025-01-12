<?php declare(strict_types=1);

namespace FlickFacts\Common\ApplicationService\Clock;

use DateTimeImmutable;

interface Clock
{
    /**
     * Retrieves the current date and time as a `DateTimeImmutable` object.
     *
     * @param string $when A date/time string recognized by `DateTimeImmutable`. Defaults to 'now'.
     * @return DateTimeImmutable The current date and time.
     */
    public function now(string $when = 'now'): DateTimeImmutable;
}