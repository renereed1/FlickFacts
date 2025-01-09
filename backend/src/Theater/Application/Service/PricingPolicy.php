<?php

namespace FlickFacts\Theater\Application\Service;


interface PricingPolicy
{
    /**
     * Retrieves the price for a specific movie at a given theater.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     *
     * @return float|null The price of the movie at the theater, or `null` if not available.
     */
    public function getPrice(string $theaterId,
                             string $movieId): ?float;
}