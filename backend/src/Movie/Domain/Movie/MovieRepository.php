<?php declare(strict_types=1);

namespace FlickFacts\Movie\Domain\Movie;

use FlickFacts\Movie\Domain\Movie\Entity\Movie;

interface MovieRepository
{
    /**
     * Persists a new Movie entity to the repository.
     *
     * @param Movie $movie The Movie entity to create.
     * @return void
     */
    public function createMovie(Movie $movie): void;
}