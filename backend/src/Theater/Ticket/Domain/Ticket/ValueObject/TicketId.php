<?php declare(strict_types=1);

namespace FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject;

readonly class TicketId
{
    public function __construct(public string $id)
    {

    }
}