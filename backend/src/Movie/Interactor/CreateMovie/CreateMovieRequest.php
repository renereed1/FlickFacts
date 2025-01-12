<?php declare(strict_types=1);

namespace FlickFacts\Movie\Interactor\CreateMovie;

readonly class CreateMovieRequest
{
    public function __construct(public string $title,
                                public string $description)
    {

    }
}