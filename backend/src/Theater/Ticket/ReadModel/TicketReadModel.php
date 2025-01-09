<?php

namespace FlickFacts\Theater\Ticket\ReadModel;

interface TicketReadModel
{
    /**
     * Retrieves all tickets for a specific theater.
     *
     * @param string $theaterId The ID of the theater.
     *
     * @return array An array of ticket details, where each detail includes:
     *               - `ticket_id`: The ticket ID.
     *               - `movie_id`: The associated movie ID.
     *               - `movie_title`: The movie title.
     *               - `price`: The ticket price.
     *               - `total`: The total number of tickets.
     *               - `available`: The number of available tickets.
     */
    public function findTickets(string $theaterId): array;

    /**
     * Checks if a specific movie is available for a given theater.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     *
     * @return bool True if the movie is available, otherwise false.
     */
    public function isTicketAvailable(string $theaterId,
                                      string $movieId): bool;
}