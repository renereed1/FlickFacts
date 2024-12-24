<?php

namespace FlickFacts\Theater\Domain\Theater\ValueObject;

readonly class Name
{
    public function __construct(public string $name)
    {
        
    }
}