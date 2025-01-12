<?php declare(strict_types=1);

namespace FlickFacts\Theater\Application\Service;

use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;

interface DiscountService
{

    /**
     * Retrieves the discount based on the provided discount code.
     *
     * @param string $code The discount code.
     *
     * @return Discount The corresponding Discount object.
     */
    public function getDiscount(string $code): Discount;
}