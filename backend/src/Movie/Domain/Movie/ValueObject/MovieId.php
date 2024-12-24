<?php

namespace FlickFacts\Movie\Domain\Movie\ValueObject;

readonly class MovieId
{
    public function __construct(public string $id)
    {

    }
}