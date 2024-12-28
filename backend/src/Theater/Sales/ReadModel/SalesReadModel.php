<?php

namespace FlickFacts\Theater\Sales\ReadModel;

use DateTimeInterface;

interface SalesReadModel
{
    public function findTheaterWithHighestSales(DateTimeInterface $when): array;
}