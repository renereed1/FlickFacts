<?php

namespace FlickFacts\Theater\Domain\Theater\ValueObject;

readonly class MovieId
{
    public function __construct(public string $id)
    {
        
    }
}