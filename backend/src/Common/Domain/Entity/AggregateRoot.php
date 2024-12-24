<?php

namespace FlickFacts\Common\Domain\Entity;

use DateTimeInterface;

abstract class AggregateRoot extends Entity
{
    public function __construct(string            $id,
                                DateTimeInterface $createdAt,
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