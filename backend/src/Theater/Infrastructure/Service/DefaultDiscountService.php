<?php declare(strict_types=1);

namespace FlickFacts\Theater\Infrastructure\Service;

use FlickFacts\Theater\Application\Service\DiscountService;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;

class DefaultDiscountService implements DiscountService
{

    public function getDiscount(string $code): Discount
    {
        return match ($code) {
            'everyone-10' => new Discount(10),
            default => new Discount()
        };
    }
}