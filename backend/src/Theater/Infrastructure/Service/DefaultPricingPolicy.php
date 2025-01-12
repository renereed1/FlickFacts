<?php

namespace FlickFacts\Theater\Infrastructure\Service;

use FlickFacts\Theater\Application\Service\PricingPolicy;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;

readonly class DefaultPricingPolicy implements PricingPolicy
{
    public function __construct(private TicketRepository $ticketRepository)
    {

    }

    /**
     * Retrieves the price for a specific movie at a given theater.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     *
     * @return Price|null The price of the movie at the specified theater, or null if not found.
     */
    public function getPrice(string $theaterId,
                             string $movieId): ?Price
    {
        $ticket = $this->ticketRepository->findTicketByTheaterIdAndMovieId(theaterId: new TheaterId(id: $theaterId),
            movieId: new MovieId(id: $movieId));

        return $ticket?->price();
    }
}