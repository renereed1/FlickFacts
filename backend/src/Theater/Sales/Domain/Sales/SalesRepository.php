<?php

namespace FlickFacts\Theater\Sales\Domain\Sales;

use FlickFacts\Theater\Sales\Domain\Sales\Entity\Sales;

interface SalesRepository
{

    public function createSale(Sales $sales): void;
}