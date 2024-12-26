<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket;

use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\MovieId;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TheaterId;

interface TicketRepository
{
    public function createTicket(Ticket $ticket): void;

    public function findBy(TheaterId $theaterId,
                           MovieId   $movieId): ?Ticket;

    public function save(?Ticket $ticket): void;
}