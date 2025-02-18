<?php declare(strict_types=1);

namespace FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject;

use RuntimeException;

readonly class Quantity
{
    public function __construct(public int $quantity)
    {
        if ($quantity < 1) {
            throw new RuntimeException('Quantity must be positive and greater then 0.');
        }
    }
}