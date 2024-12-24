<?php

namespace FlickFacts\Common\Domain\Entity;

use DateTimeInterface;

abstract class Entity
{

    public function __construct(protected string            $id,
                                protected DateTimeInterface $createdAt)
    {

    }
}