<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Domain\Sales\ValueObject;

readonly class SalesId
{
    public function __construct(public string $id)
    {

    }
}