<?php

namespace FlickFacts\Theater\Infrastructure\Service;

use FlickFacts\Theater\Application\Service\TicketService;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;

readonly class DefaultTicketService implements TicketService
{
    public function __construct(private TicketRepository $ticketRepository)
    {

    }

    public function allocateTickets(string $theaterId,
                                    string $movieId,
                                    int    $quantity): void
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: $theaterId,
            movieId: $movieId);

        $ticket->allocateTickets($quantity);

        $this->ticketRepository->save($ticket);
    }

    public function releaseTickets(string $theaterId,
                                   string $movieId,
                                   int    $quantity): void
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: $theaterId,
            movieId: $movieId);

        $ticket->releaseTickets($quantity);

        $this->ticketRepository->save($ticket);
    }
}