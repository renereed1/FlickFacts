<?php

namespace FlickFacts\Theater\Sales\Domain\Sales\ValueObject;

readonly class TheaterId
{
    public function __construct(public string $id)
    {

    }
}