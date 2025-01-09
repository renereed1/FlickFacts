<?php

namespace FlickFacts\Movie\ReadModel;

interface MovieReadModel
{

    /**
     * Retrieves a list of all movies along with their sales and revenue data.
     *
     * @return array A list of movies, where each movie contains:
     *               - `id`: The unique identifier of the movie.
     *               - `title`: The title of the movie.
     *               - `quantity`: The total number of tickets sold.
     *               - `total_revenue`: The total revenue generated from sales.
     */
    public function findMovies(): array;

    /**
     * Retrieves detailed information about a specific movie by its ID.
     *
     * @param string $movieId The unique identifier of the movie.
     *
     * @return array The movie details, including:
     *               - `id`: The unique identifier of the movie.
     *               - `title`: The title of the movie.
     *               - `description`: The description of the movie.
     */
    public function findMovie(string $movieId): array;
}