<?php

namespace FlickFacts\Tests\Unit\Theater\Ticket\Interactor;

use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\MovieId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicketRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateTicketTest extends TestCase
{
    private CreateTicket $createTicket;

    private IdGenerator $idGenerator;

    private Clock $clock;

    private TicketRepository $ticketRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->idGenerator = M::mock(IdGenerator::class);
        $this->idGenerator->expects('nextId')
            ->andReturn('TICKET_1');

        $this->clock = M::mock(Clock::class);
        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('2001-12-15T03:13:32+00:00'));

        $ticket = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2001-12-15T03:13:32+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 12.34,
            total: 20,
            available: 20);

        $this->ticketRepository = M::mock(TicketRepository::class);
        $this->ticketRepository->expects('createTicket')
            ->with(M::on(function ($args) use ($ticket) {
                return $args == $ticket;
            }));

        $this->createTicket = new CreateTicket(idGenerator: $this->idGenerator,
            clock: $this->clock,
            ticketRepository: $this->ticketRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CreateTicket(): void
    {
        $request = new CreateTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            price: 12.34,
            total: 20);

        $this->createTicket->execute($request);
    }
}