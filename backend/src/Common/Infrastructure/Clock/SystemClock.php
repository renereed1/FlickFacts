<?php

namespace FlickFacts\Common\Infrastructure\Clock;

use DateMalformedStringException;
use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;

class SystemClock implements Clock
{

    /**
     * @throws DateMalformedStringException
     */
    public function now(string $when = 'now'): DateTimeImmutable
    {
        return new DateTimeImmutable($when);
    }
}