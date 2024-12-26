<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject;

readonly class TicketId
{
    public function __construct(public string $id)
    {
        
    }
}