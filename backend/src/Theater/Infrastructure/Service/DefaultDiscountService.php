<?php declare(strict_types=1);

namespace FlickFacts\Theater\Infrastructure\Service;

use Exception;
use FlickFacts\Theater\Application\Service\DiscountService;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;

class DefaultDiscountService implements DiscountService
{

    /**
     * Gets the discount based on the provided code.
     *
     * @param string $code The discount code.
     *
     * @return Discount The corresponding Discount object.
     * @throws Exception
     */
    public function getDiscount(string $code): Discount
    {
        return match ($code) {
            'everyone-10' => new Discount(10),
            default => new Discount()
        };
    }
}