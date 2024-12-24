<?php

namespace FlickFacts\Theater\Domain\Theater\Entity;

use DateTimeInterface;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Domain\Theater\ValueObject\Name;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;

class Theater extends AggregateRoot
{
    public function __construct(public readonly TheaterId $theaterId,
                                DateTimeInterface         $createdAt,
                                public Name               $name)
    {
        parent::__construct(id: $this->theaterId->id,
            createdAt: $createdAt,
            version: 1);
    }
}