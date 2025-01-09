<?php

namespace FlickFacts\Theater\Domain\Theater\Entity;

use DateTimeInterface;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Domain\Theater\ValueObject\Name;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;

class Theater extends AggregateRoot
{
    public function __construct(public readonly TheaterId $theaterId,
                                DateTimeInterface         $createdAt,
                                public Name               $name)
    {
        parent::__construct(id: $this->theaterId->id,
            createdAt: $createdAt,
            version: 1);
    }

    /**
     * Serializes the Theater entity into an array format.
     *
     * @return array An associative array containing the serialized theater data.
     */

    public function serialize(): array
    {
        return [
            'id' => $this->theaterId->id,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'name' => $this->name->name,
        ];
    }
}