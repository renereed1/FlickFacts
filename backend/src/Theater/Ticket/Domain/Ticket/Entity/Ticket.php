<?php

namespace FlickFacts\Theater\Ticket\Domain\Ticket\Entity;

use DateTimeImmutable;
use Exception;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use RuntimeException;

class Ticket extends AggregateRoot
{
    public function __construct(public readonly TicketId $ticketId,
                                DateTimeImmutable        $createdAt,
                                private TheaterId        $theaterId,
                                private MovieId          $movieId,
                                private float            $price,
                                private int              $total,
                                private int              $available)
    {
        parent::__construct(id: $ticketId->id,
            createdAt: $createdAt,
            version: 1);
    }

    /**
     * Retrieves the price of the ticket.
     *
     * @return float The ticket price.
     */
    public function price(): float
    {
        return $this->price;
    }

    /**
     * Allocates a specified number of tickets.
     *
     * @param int $quantity The number of tickets to allocate.
     *
     * @throws Exception If there are not enough tickets available to allocate.
     */
    public function allocateTickets(int $quantity): void
    {
        if ($this->available < $quantity) {
            throw new RuntimeException('Insufficient tickets available to allocate.');
        }

        $this->available -= $quantity;
    }

    /**
     * Releases a specified number of tickets back to availability.
     *
     * @param int $quantity The number of tickets to release.
     *
     * @throws Exception If releasing the tickets exceeds the total ticket capacity.
     */
    public function releaseTickets(int $quantity): void
    {
        if ($this->available + $quantity > $this->total) {
            throw new RuntimeException('Cannot release tickets: exceeds total ticket capacity.');
        }

        $this->available += $quantity;
    }

    /**
     * Serializes the ticket entity to an array.
     *
     * @return array Serialized representation of the ticket.
     */
    public function serialize(): array
    {
        return [
            'id' => $this->ticketId->id,
            'theaterId' => $this->theaterId->id,
            'movieId' => $this->movieId->id,
            'price' => $this->price,
            'total' => $this->total,
            'available' => $this->available,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}