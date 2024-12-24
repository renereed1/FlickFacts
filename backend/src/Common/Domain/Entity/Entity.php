<?php

namespace FlickFacts\Common\Domain\Entity;

use DateTimeImmutable;

abstract class Entity
{

    public function __construct(protected string            $id,
                                protected DateTimeImmutable $createdAt)
    {

    }
}