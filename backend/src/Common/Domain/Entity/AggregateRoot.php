<?php

namespace FlickFacts\Common\Domain\Entity;

use DateTimeImmutable;

abstract class AggregateRoot extends Entity
{
    public function __construct(string            $id,
                                DateTimeImmutable $createdAt,
                                protected int     $version)
    {
        parent::__construct(id: $id,
            createdAt: $createdAt);
    }

    protected function incrementVersion(): void
    {
        $this->version++;
    }
}