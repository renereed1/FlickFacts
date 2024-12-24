<?php

namespace FlickFacts\Theater\Interactor\CreateTheater;

readonly class CreateTheaterRequest
{
    public function __construct(public string $name)
    {
    }
}