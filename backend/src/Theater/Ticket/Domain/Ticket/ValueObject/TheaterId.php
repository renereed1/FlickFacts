<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject;

readonly class TheaterId
{
    public function __construct(public string $id)
    {
        
    }
}