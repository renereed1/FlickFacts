<?php

namespace FlickFacts\Movie\Domain\Movie\ValueObject;

use RuntimeException;

readonly class Description
{
    public function __construct(public string $description)
    {
        if (strlen($this->description) > 120) {
            throw new RuntimeException('Description must be less than 120 characters long');
        }
    }
}