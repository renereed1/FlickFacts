<?php

namespace FlickFacts\Theater\Ticket\Interactor\CreateTicket;

use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use RuntimeException;

class CreateTicket
{
    public function __construct(private readonly IdGenerator      $idGenerator,
                                private readonly Clock            $clock,
                                private readonly TicketRepository $ticketRepository,
                                private readonly TicketReadModel  $ticketReadModel)
    {

    }

    /**
     * Executes the ticket creation process.
     *
     * @param CreateTicketRequest $request Contains the details required to create a ticket.
     * @return void
     */
    public function execute(CreateTicketRequest $request): void
    {
        if ($this->ticketReadModel->isTicketAvailable(theaterId: $request->theaterId,
            movieId: $request->movieId)) {

            throw new RuntimeException('Ticket exists with availability.');
        }

        $ticket = $this->createTicket(theaterId: $request->theaterId,
            movieId: $request->movieId,
            price: $request->price,
            total: $request->total);

        // Additional logic can be implemented based on the ticket aggregate
    }

    /**
     * Creates a new Ticket entity.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     * @param float $price The price of a single ticket.
     * @param int $total The total number of tickets available.
     * @return Ticket The created Ticket entity.
     */
    public function createTicket(string $theaterId,
                                 string $movieId,
                                 float  $price,
                                 int    $total): Ticket
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $ticket = new Ticket(new TicketId(id: $id),
            createdAt: $createdAt,
            theaterId: new TheaterId(id: $theaterId),
            movieId: new MovieId(id: $movieId),
            price: $price,
            total: $total,
            available: $total);

        $this->ticketRepository->createTicket($ticket);

        return $ticket;
    }
}