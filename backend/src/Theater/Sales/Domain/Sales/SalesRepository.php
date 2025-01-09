<?php

namespace FlickFacts\Theater\Sales\Domain\Sales;

use FlickFacts\Theater\Sales\Domain\Sales\Entity\Sales;

interface SalesRepository
{

    /**
     * Persists a new Sales entity to the repository.
     *
     * @param Sales $sales The Sales entity to create.
     * @return void
     */
    public function createSale(Sales $sales): void;
}