<?php

namespace FlickFacts\Movie\Domain\Movie\ValueObject;

readonly class Title
{
    public function __construct(public string $title)
    {

    }
}