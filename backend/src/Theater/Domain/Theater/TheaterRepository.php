<?php declare(strict_types=1);

namespace FlickFacts\Theater\Domain\Theater;

use FlickFacts\Theater\Domain\Theater\Entity\Theater;

interface TheaterRepository
{
    /**
     * Persists a new Theater entity to the repository.
     *
     * @param Theater $theater The Theater entity to create.
     * @return void
     */
    public function createTheater(Theater $theater): void;
}