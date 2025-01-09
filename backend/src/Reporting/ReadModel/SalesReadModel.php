<?php

namespace FlickFacts\Reporting\ReadModel;

use DateTimeInterface;

interface SalesReadModel
{
    /**
     * Retrieves the Theater with the highest sales for a specific date.
     *
     * @param DateTimeInterface $when The date for which to retrieve the highest sales.
     *
     * @return array|null An array containing sales data for the highest sales on the given date.
     */
    public function findTheaterWithHighestSalesByDate(DateTimeInterface $when): ?array;

    /**
     * Retrieves sales details for movies associated with a specific theater ID.
     *
     * @param string $theaterId The ID of the theater.
     *
     * @return array An array of sales data for movies at the specified theater.
     */
    public function findTheaterMovieSales(string $theaterId): array;

    /**
     * Retrieves sales details for a specific movie across different theaters.
     *
     * @param string $movieId The ID of the movie.
     *
     * @return array An array of sales data for the specified movie at various theaters.
     */
    public function findMovieTheaterSales(string $movieId): array;
}