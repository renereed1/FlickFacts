<?php declare(strict_types=1);

namespace FlickFacts\Theater\Application\Service;

use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\Quantity;

interface TicketService
{
    /**
     * Allocates a specific number of tickets for a given movie at a specified theater.
     *
     * @param TheaterId $theaterId The ID of the theater.
     * @param MovieId $movieId The ID of the movie.
     * @param Quantity $quantity The number of tickets to allocate.
     *
     * @return void
     */
    public function allocateTickets(TheaterId $theaterId,
                                    MovieId   $movieId,
                                    Quantity  $quantity): void;

    /**
     * Releases a specific number of tickets for a given movie at a specified theater.
     *
     * @param TheaterId $theaterId The ID of the theater.
     * @param MovieId $movieId The ID of the movie.
     * @param Quantity $quantity The number of tickets to release.
     *
     * @return void
     */
    public function releaseTickets(TheaterId $theaterId,
                                   MovieId   $movieId,
                                   Quantity  $quantity): void;
}