<?php

namespace FlickFacts\Theater\Domain\Theater\ValueObject;

use RuntimeException;

readonly class Name
{
    public function __construct(public string $name)
    {
        if (strlen($this->name) < 2 || strlen($this->name) > 20) {
            throw new RuntimeException('Name must be between 2 and 20 characters long');
        }

        if (!preg_match('/^[a-zA-Z0-9\s\-\_\.]+$/', $this->name)) {
            throw new RuntimeException('Name can not contain non-alphanumeric characters');
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}