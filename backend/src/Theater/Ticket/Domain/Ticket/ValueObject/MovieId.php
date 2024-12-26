<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject;

readonly class MovieId
{
    public function __construct(public string $id)
    {

    }
}