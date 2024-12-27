<?php

namespace FlickFacts\Common\ApplicationService\IdGenerator;

interface IdGenerator
{
    /**
     * Generates and returns a new unique identifier.
     *
     * @return string A unique identifier in string format.
     */
    public function nextId(): string;
}