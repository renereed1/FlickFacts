<?php

namespace FlickFacts\Movie\ReadModel;

interface MovieReadModel
{

    public function findMovies(): array;

    public function findMovie(string $movieId): array;
}