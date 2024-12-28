<?php

namespace FlickFacts\Theater\Sales\Domain\Sales\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\SalesId;

class Sales extends AggregateRoot
{
    public function __construct(public readonly SalesId   $salesId,
                                DateTimeImmutable         $createdAt,
                                public readonly TheaterId $theaterId,
                                public readonly MovieId   $movieId,
                                private readonly float    $price,
                                public readonly float     $quantity)
    {
        parent::__construct(id: $salesId->id,
            createdAt: $createdAt,
            version: 1);
    }

    /**
     * Serializes the Sales entity to an array.
     *
     * @return array Serialized representation of the Sales entity.
     */
    public function serialize(): array
    {
        return [
            'id' => $this->salesId->id,
            'theaterId' => $this->theaterId->id,
            'movieId' => $this->movieId->id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
        ];
    }
}