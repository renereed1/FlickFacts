<?php

namespace FlickFacts\Movie\ReadModel;

interface MovieReadModel
{

    public function getMovies(): array;

    public function findMovie(string $movieId): array;
}