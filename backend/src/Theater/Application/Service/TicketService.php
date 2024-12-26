<?php

namespace FlickFacts\Theater\Application\Service;

interface TicketService
{

    public function allocateTickets(string $theaterId,
                                    string $movieId,
                                    int    $quantity): void;

    public function releaseTickets(string $theaterId,
                                   string $movieId,
                                   int    $quantity): void;
}