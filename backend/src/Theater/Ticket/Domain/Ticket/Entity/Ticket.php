<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\Entity;

use DateTimeImmutable;
use Exception;
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
                                private float            $price,
                                private int              $total,
                                private int              $available)
    {
        parent::__construct(id: $ticketId->id,
            createdAt: $createdAt,
            version: 1);
    }

    public function price(): float
    {
        return $this->price;
    }

    public function allocateTickets(int $quantity): void
    {
        if ($this->available < $quantity) {
            throw new Exception('Insufficient tickets available to allocate.');
        }

        $this->available -= $quantity;
    }

    public function releaseTickets(int $quantity): void
    {
        if ($this->available + $quantity > $this->total) {
            throw new Exception('Cannot release tickets: exceeds total ticket capacity.');
        }
        $this->available += $quantity;
    }
}