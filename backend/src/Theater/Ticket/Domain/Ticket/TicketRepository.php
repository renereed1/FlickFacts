<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket;

use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;

interface TicketRepository
{
    public function createTicket(Ticket $ticket): void;

    public function save(?Ticket $ticket): void;

    public function findTicketByTheaterIdAndMovieId(string $theaterId,
                                                    string $movieId): ?Ticket;
}