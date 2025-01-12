<?php declare(strict_types=1);

namespace FlickFacts\Common\Domain\Entity;

use DateTimeInterface;

abstract class AggregateRoot extends Entity
{
    public function __construct(string            $id,
                                DateTimeInterface $createdAt,
                                protected int     $version)
    {
        parent::__construct(id: $id,
            createdAt: $createdAt);
    }

    /**
     * Increments the version number of the aggregate root.
     *
     * This method is typically used to track changes made to the aggregate.
     *
     * @return void
     */
    protected function incrementVersion(): void
    {
        $this->version++;
    }
}