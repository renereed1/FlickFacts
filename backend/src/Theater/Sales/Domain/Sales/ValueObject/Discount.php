<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Domain\Sales\ValueObject;

use Exception;

class Discount
{
    private(set) int $discount;
    private(set) float $percent;

    public function __construct(int $discount = 0)
    {
        if ($discount < 0 || $discount > 100) {
            throw new Exception('Discount must be between 0.0 and 1.0');
        }

        $this->discount = $discount;

        $percentageDecimal = $discount / 100;

        $this->percent = $percentageDecimal;
    }
}