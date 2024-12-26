<?php

namespace FlickFacts\Theater\Sales\Domain\Sales\Entity;

use DateTimeImmutable;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\MovieId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\SalesId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\TheaterId;

class Sales extends AggregateRoot
{
    public function __construct(public readonly SalesId   $salesId,
                                DateTimeImmutable         $createdAt,
                                public readonly TheaterId $theaterId,
                                public readonly MovieId   $movieId,
                                private float             $price,
                                public readonly float     $quantity)
    {
        parent::__construct(id: $salesId->id,
            createdAt: $createdAt,
            version: 1);
    }
}