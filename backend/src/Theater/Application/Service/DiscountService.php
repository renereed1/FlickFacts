<?php declare(strict_types=1);

namespace FlickFacts\Theater\Application\Service;

use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;

interface DiscountService
{

    public function getDiscount(string $code): Discount;
}