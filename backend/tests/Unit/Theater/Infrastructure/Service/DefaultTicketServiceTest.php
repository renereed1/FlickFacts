<?php

namespace FlickFacts\Tests\Unit\Theater\Infrastructure\Service;

use DateTimeImmutable;
use Exception;
use FlickFacts\Theater\Application\Service\TicketService;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Infrastructure\Service\DefaultTicketService;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DefaultTicketServiceTest extends TestCase
{
    private TicketService $ticketService;

    private TicketRepository $ticketRepository;

    public function setUp(): void
    {
        parent::setUp();

        $ticket = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 20);


        $this->ticketRepository = M::mock(TicketRepository::class);
        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturn($ticket)
            ->byDefault();

        $this->ticketService = new DefaultTicketService(ticketRepository: $this->ticketRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CanAllocateTicket(): void
    {
        $purchased = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 19);

        $this->ticketRepository->expects('save')
            ->with(M::on(function ($args) use ($purchased) {
                return $args == $purchased;
            }));


        $this->ticketService->allocateTickets(theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            quantity: 1);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CanReleaseTicket(): void
    {
        $purchased = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 19);

        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturn($purchased);

        $released = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 20);

        $this->ticketRepository->expects('save')
            ->with(M::on(function ($args) use ($released) {
                return $args == $released;
            }));

        $this->ticketService->releaseTickets(theaterId: new TheaterId('THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            quantity: 1);
    }

    #[Test]
    public function AllocateTicketsThrowsExceptionWhenNoTicketsAvailable(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient tickets available to allocate.');

        $this->ticketRepository->expects('save')
            ->never();

        $purchased = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 0);

        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturn($purchased);

        $this->ticketService->allocateTickets(theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            quantity: 1);
    }

    #[Test]
    public function ReleaseTicketsThrowsExceptionWhenAvailableTicketsIsMoreThenTotal(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot release tickets: exceeds total ticket capacity.');

        $this->ticketRepository->expects('save')
            ->never();

        $purchased = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 20);

        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturn($purchased);

        $this->ticketService->releaseTickets(theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            quantity: 1);
    }

    #[Test]
    public function AllocateTicketsThrowsExceptionWhenQuantityIsNegative(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Quantity must be positive and greater then 0.');

        $this->ticketRepository->expects('save')
            ->never();

        $purchased = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable('2000-07-25T10:03:23+00:00'),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 0);

        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturn($purchased);

        $this->ticketService->allocateTickets(theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            quantity: -1);
    }
}