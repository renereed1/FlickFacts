<?php

namespace FlickFacts\Common\Infrastructure\IdGenerator;

use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use Ramsey\Uuid\Uuid;

class UuidIdGenerator implements IdGenerator
{
    /**
     * Generates a new unique identifier.
     *
     * @return string A UUID version 4 string.
     */
    public function nextId(): string
    {
        return Uuid::uuid4()
            ->toString();
    }
}