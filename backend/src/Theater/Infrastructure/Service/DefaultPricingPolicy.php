<?php

namespace FlickFacts\Theater\Infrastructure\Service;

use FlickFacts\Theater\Application\Service\PricingPolicy;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;

readonly class DefaultPricingPolicy implements PricingPolicy
{
    public function __construct(private TicketRepository $ticketRepository)
    {

    }

    public function getPrice(string $theaterId,
                             string $movieId): ?float
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: new TheaterId(id: $theaterId),
            movieId: new MovieId(id: $movieId));

        return $ticket?->price();
    }
}