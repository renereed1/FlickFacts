<?php

namespace FlickFacts\Movie\Domain\Movie\ValueObject;

use RuntimeException;

readonly class Title
{
    public function __construct(public string $title)
    {
        if (strlen($this->title) < 2 || strlen($this->title) > 20) {
            throw new RuntimeException('Title must be between 2 and 20 characters long');
        }
    }
}