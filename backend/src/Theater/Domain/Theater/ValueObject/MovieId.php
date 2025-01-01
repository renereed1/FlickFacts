<?php

namespace FlickFacts\Theater\Domain\Theater\ValueObject;

use RuntimeException;

readonly class MovieId
{
    public function __construct(public string $id)
    {
        if (empty($this->id)) {
            throw new RuntimeException('Movie cannot be empty');
        }
    }
}