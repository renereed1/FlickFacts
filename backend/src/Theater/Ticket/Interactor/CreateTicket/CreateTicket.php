<?php

namespace FlickFacts\Theater\Ticket\Interactor\CreateTicket;

use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;
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

        $ticket = $this->createTicket(theaterId: new TheaterId($request->theaterId),
            movieId: new MovieId($request->movieId),
            price: new Price(price: $request->price),
            total: $request->total);

        // Additional logic can be implemented based on the ticket aggregate
    }

    /**
     * Creates a new Ticket entity.
     *
     * @param TheaterId $theaterId The ID of the theater.
     * @param MovieId $movieId The ID of the movie.
     * @param Price $price The price of a single ticket.
     * @param int $total The total number of tickets available.
     * @return Ticket The created Ticket entity.
     */
    public function createTicket(TheaterId $theaterId,
                                 MovieId   $movieId,
                                 Price     $price,
                                 int       $total): Ticket
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $ticket = new Ticket(new TicketId(id: $id),
            createdAt: $createdAt,
            theaterId: $theaterId,
            movieId: $movieId,
            price: $price,
            total: $total,
            available: $total);

        $this->ticketRepository->createTicket($ticket);

        return $ticket;
    }
}