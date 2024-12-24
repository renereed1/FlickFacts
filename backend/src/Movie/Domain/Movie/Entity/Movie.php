<?php

namespace FlickFacts\Movie\Domain\Movie\Entity;

use DateTimeInterface;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Movie\Domain\Movie\ValueObject\Description;
use FlickFacts\Movie\Domain\Movie\ValueObject\MovieId;
use FlickFacts\Movie\Domain\Movie\ValueObject\Title;

class Movie extends AggregateRoot
{
    public function __construct(public readonly MovieId $movieId,
                                DateTimeInterface       $createdAt,
                                private Title           $title,
                                private Description     $description,)
    {
        parent::__construct(id: $movieId->id,
            createdAt: $createdAt,
            version: 1);
    }
}