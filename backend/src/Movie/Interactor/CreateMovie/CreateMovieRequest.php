<?php

namespace FlickFacts\Movie\Interactor\CreateMovie;

readonly class CreateMovieRequest
{
    public function __construct(public string $title,
                                public string $description)
    {

    }
}