<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket;

use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;

interface TicketRepository
{
    /**
     * Persists a new Ticket in the repository.
     *
     * @param Ticket $ticket The Ticket entity to be created.
     */
    public function createTicket(Ticket $ticket): void;

    /**
     * Saves changes to an existing Ticket entity in the repository.
     *
     * @param Ticket $ticket The Ticket entity to be saved.
     */
    public function save(Ticket $ticket): void;

    /**
     * Finds a Ticket entity by its associated Theater ID and Movie ID.
     *
     * @param TheaterId $theaterId The ID of the theater associated with the ticket.
     * @param MovieId $movieId The ID of the movie associated with the ticket.
     *
     * @return Ticket|null The Ticket entity if found, or null if not.
     */
    public function findTicketByTheaterIdAndMovieId(TheaterId $theaterId,
                                                    MovieId   $movieId): ?Ticket;
}