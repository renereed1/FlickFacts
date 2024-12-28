<?php

namespace FlickFacts\Tests\Unit\Theater\Sales\ReadModel;

use DateTimeInterface;
use FlickFacts\Theater\Domain\Theater\Entity\Theater;

interface SalesReadModel
{
    /**
     * Retrieves the Theater with the highest sales for a specific date.
     *
     * @param DateTimeInterface $date The date for which to retrieve the highest sales.
     *
     * @return Theater An array containing sales data for the highest sales on the given date.
     */
    public function findTheaterWithHighestSalesByDate(DateTimeInterface $date): Theater;
}