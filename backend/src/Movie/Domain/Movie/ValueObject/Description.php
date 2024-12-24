<?php

namespace FlickFacts\Movie\Domain\Movie\ValueObject;

readonly class Description
{
    public function __construct(public string $description)
    {

    }
}