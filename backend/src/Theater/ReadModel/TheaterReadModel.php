<?php

namespace FlickFacts\Theater\ReadModel;

interface TheaterReadModel
{
    /**
     * Retrieves a list of all theaters.
     *
     * @return array An array of theaters.
     */
    public function findTheaters(): array;

    /**
     * Retrieves details of a specific theater by its ID.
     *
     * @param string $theaterId The ID of the theater.
     *
     * @return array An array containing the theater's details.
     */
    public function findTheater(string $theaterId): array;
}