<?php

namespace FlickFacts\Theater\ReadModel;

interface TheaterReadModel
{
    public function findTheaters(): array;

    public function findTheater(string $theaterId): array;
}