<?php

namespace FlickFacts\Theater\Infrastructure\Service;

use FlickFacts\Theater\Application\Service\PricingPolicy;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;

readonly class DefaultPricingPolicy implements PricingPolicy
{
    public function __construct(private TicketRepository $ticketRepository)
    {

    }

    public function getPrice(string $theaterId,
                             string $movieId): ?float
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: $theaterId,
            movieId: $movieId);

        return $ticket?->price();
    }
}