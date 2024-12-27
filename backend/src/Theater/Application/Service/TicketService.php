<?php

namespace FlickFacts\Theater\Application\Service;

use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;

interface TicketService
{

    public function allocateTickets(TheaterId $theaterId,
                                    MovieId   $movieId,
                                    int       $quantity): void;

    public function releaseTickets(TheaterId $theaterId,
                                   MovieId   $movieId,
                                   int       $quantity): void;
}