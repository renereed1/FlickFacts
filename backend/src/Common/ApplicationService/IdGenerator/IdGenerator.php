<?php

namespace FlickFacts\Common\ApplicationService\IdGenerator;

interface IdGenerator
{
    public function nextId(): string;
}