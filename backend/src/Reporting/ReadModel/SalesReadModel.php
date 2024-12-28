<?php

namespace FlickFacts\Reporting\ReadModel;

use DateTimeInterface;

interface SalesReadModel
{
    /**
     * Retrieves the Theater with the highest sales for a specific date.
     *
     * @param DateTimeInterface $date The date for which to retrieve the highest sales.
     *
     * @return array|null An array containing sales data for the highest sales on the given date.
     */
    public function findTheaterWithHighestSalesByDate(DateTimeInterface $date): ?array;
}