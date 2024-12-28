<?php

namespace FlickFacts\Common\Infrastructure\IdGenerator;

use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use Ramsey\Uuid\Uuid;

class UuidIdGenerator implements IdGenerator
{

    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
    }
}