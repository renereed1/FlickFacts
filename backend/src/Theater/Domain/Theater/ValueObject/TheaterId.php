<?php declare(strict_types=1);

namespace FlickFacts\Theater\Domain\Theater\ValueObject;

readonly class TheaterId
{
    public function __construct(public string $id)
    {

    }
}