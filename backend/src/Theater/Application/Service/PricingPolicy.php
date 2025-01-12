<?php

namespace FlickFacts\Theater\Application\Service;


use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;

interface PricingPolicy
{
    /**
     * Retrieves the price for a specific movie at a given theater.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     *
     * @return Price|null The price of the movie at the theater, or `null` if not available.
     */
    public function getPrice(string $theaterId,
                             string $movieId): ?Price;
}