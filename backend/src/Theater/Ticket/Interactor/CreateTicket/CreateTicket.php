<?php

namespace FlickFacts\Theater\Ticket\Interactor\CreateTicket;

use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\MovieId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;

class CreateTicket
{
    public function __construct(private readonly IdGenerator      $idGenerator,
                                private readonly Clock            $clock,
                                private readonly TicketRepository $ticketRepository)
    {

    }

    public function execute(CreateTicketRequest $request): void
    {
        $ticket = $this->createTicket($request);
    }
    
    public function createTicket(CreateTicketRequest $request): Ticket
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $ticket = new Ticket(new TicketId(id: $id),
            createdAt: $createdAt,
            theaterId: new TheaterId(id: $request->theaterId),
            movieId: new MovieId(id: $request->movieId),
            price: $request->price,
            total: $request->total,
            available: $request->total);

        $this->ticketRepository->createTicket($ticket);

        return $ticket;
    }
}