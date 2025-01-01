<?php

namespace FlickFacts\Theater\Ticket\ReadModel;

interface TicketReadModel
{

    public function getTickets(string $theaterId): array;

    public function isTicketAvailable(string $theaterId,
                                      string $movieId): bool;
}