<?php declare(strict_types=1);

namespace FlickFacts\Theater\Infrastructure\Service;

use Exception;
use FlickFacts\Theater\Application\Service\TicketService;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\Quantity;

readonly class DefaultTicketService implements TicketService
{
    public function __construct(private TicketRepository $ticketRepository)
    {

    }

    /**
     * Allocates a specified number of tickets for a given movie at a specific theater.
     *
     * @param TheaterId $theaterId The ID of the theater.
     * @param MovieId $movieId The ID of the movie.
     * @param Quantity $quantity The number of tickets to allocate.
     * @throws Exception
     */
    public function allocateTickets(TheaterId $theaterId,
                                    MovieId   $movieId,
                                    Quantity  $quantity): void
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: $theaterId,
            movieId: $movieId);

        $ticket->allocateTickets($quantity);

        $this->ticketRepository->save($ticket);
    }

    /**
     * Releases a specified number of tickets for a given movie at a specific theater.
     *
     * @param TheaterId $theaterId The ID of the theater.
     * @param MovieId $movieId The ID of the movie.
     * @param Quantity $quantity The number of tickets to release.
     * @throws Exception
     */
    public function releaseTickets(TheaterId $theaterId,
                                   MovieId   $movieId,
                                   Quantity  $quantity): void
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: $theaterId,
            movieId: $movieId);

        $ticket->releaseTickets($quantity);

        $this->ticketRepository->save($ticket);
    }
}