<?php

namespace FlickFacts\Theater\Application\Service;

interface PricingPolicy
{
    public function getPrice(string $theaterId,
                             string $movieId): float;
}