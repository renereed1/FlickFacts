<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Ticket\Interactor;

use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicketRequest;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CreateTicketTest extends TestCase
{
    private CreateTicket $createTicket;

    private IdGenerator $idGenerator;

    private Clock $clock;

    private TicketRepository $ticketRepository;

    private TicketReadModel $ticketReadModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->idGenerator = M::mock(IdGenerator::class);
        $this->idGenerator->expects('nextId')
            ->andReturn('TICKET_1')
            ->byDefault();

        $this->clock = M::mock(Clock::class);
        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('2001-12-15T03:13:32+00:00'))
            ->byDefault();

        $ticket = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2001-12-15T03:13:32+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: new Price(price: 12.34),
            total: 20,
            available: 20);

        $this->ticketRepository = M::mock(TicketRepository::class);
        $this->ticketRepository->expects('createTicket')
            ->with(M::on(function ($args) use ($ticket) {
                return $args == $ticket;
            }))
            ->byDefault();

        $this->ticketReadModel = M::mock(TicketReadModel::class);
        $this->ticketReadModel->expects('isTicketAvailable')
            ->andReturnFalse()
            ->byDefault();

        $this->createTicket = new CreateTicket(idGenerator: $this->idGenerator,
            clock: $this->clock,
            ticketRepository: $this->ticketRepository,
            ticketReadModel: $this->ticketReadModel);
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

    #[Test]
    public function AttemptCreateTicketWhenExistingTicketHasAvailability(): void
    {
        $this->expectException(RuntimeException::class);

        $this->ticketReadModel->expects('isTicketAvailable')
            ->andReturnTrue();

        $this->idGenerator->expects('nextId')
            ->never();

        $this->clock->expects('now')
            ->never();

        $this->ticketRepository->expects('createTicket')
            ->never();

        $request = new CreateTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            price: 12.34,
            total: 20);

        $this->createTicket->execute($request);
    }

    #[Test]
    public function ThrowExceptionIfRequestDataIsMissing(): void
    {
        $this->expectException(RuntimeException::class);

        $this->ticketReadModel->expects('isTicketAvailable')
            ->never();

        $this->idGenerator->expects('nextId')
            ->never();

        $this->clock->expects('now')
            ->never();

        $this->ticketRepository->expects('createTicket')
            ->never();

        $request = new CreateTicketRequest(theaterId: 'THEATER_1',
            movieId: '',
            price: 0,
            total: 0);

        $this->createTicket->execute($request);
    }
}