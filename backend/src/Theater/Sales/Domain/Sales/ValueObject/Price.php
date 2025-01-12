<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Domain\Sales\ValueObject;

use InvalidArgumentException;

readonly class Price
{
    public function __construct(public float $price)
    {
        if ($this->price < 0.0) {
            throw new InvalidArgumentException('Price cannot be less than 0');
        }
    }
}