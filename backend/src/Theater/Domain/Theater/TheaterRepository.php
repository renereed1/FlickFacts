<?php

use FlickFacts\Theater\Domain\Theater\Entity\Theater;

interface TheaterRepository
{

    public function createTheater(Theater $theater): void;
}