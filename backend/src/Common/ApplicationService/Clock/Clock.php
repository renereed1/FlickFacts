<?php

namespace FlickFacts\Common\ApplicationService\Clock;

use DateTimeImmutable;

interface Clock
{
    public function now(string $when = 'now'): DateTimeImmutable;
}