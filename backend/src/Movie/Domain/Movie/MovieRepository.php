<?php

namespace FlickFacts\Movie\Domain\Movie;

use FlickFacts\Movie\Domain\Movie\Entity\Movie;

interface MovieRepository
{

    public function createMovie(Movie $movie);
}