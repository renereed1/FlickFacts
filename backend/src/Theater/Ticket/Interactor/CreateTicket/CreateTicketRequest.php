<?php

namespace FlickFacts\Theater\Ticket\Interactor\CreateTicket;

readonly class CreateTicketRequest
{
    public function __construct(public string $theaterId,
                                public string $movieId,
                                public float  $price,
                                public int    $total)
    {

    }
}