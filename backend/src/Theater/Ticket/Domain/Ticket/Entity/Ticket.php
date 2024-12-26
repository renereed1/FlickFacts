<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\Entity;

use DateTimeImmutable;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\MovieId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;

class Ticket extends AggregateRoot
{
    public function __construct(public readonly TicketId $ticketId,
                                DateTimeImmutable        $createdAt,
                                private TheaterId        $theaterId,
                                private MovieId          $movieId,
                                private int              $total,
                                private int              $available)
    {
        parent::__construct(id: $ticketId->id,
            createdAt: $createdAt,
            version: 1);
    }
}