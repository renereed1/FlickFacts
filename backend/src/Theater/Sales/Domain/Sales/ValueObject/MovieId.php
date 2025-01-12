<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Domain\Sales\ValueObject;

readonly class MovieId
{
    public function __construct(public string $id)
    {

    }
}