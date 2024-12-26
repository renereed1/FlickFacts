<?php

namespace FlickFacts\Tests\Unit\Theater\Infrastructure\Service;

use DateTimeImmutable;
use FlickFacts\Theater\Infrastructure\Service\DefaultPricingPolicy;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\MovieId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DefaultPricingPolicyTest extends TestCase
{
    private DefaultPricingPolicy $pricingPolicy;

    private TicketRepository $ticketRepository;

    public function setUp(): void
    {
        parent::setUp();

        $ticket = new Ticket(ticketId: new TicketId('TICKET_1'),
            createdAt: new DateTimeImmutable(),
            theaterId: new TheaterId(id: 'THEATER_1'),
            movieId: new MovieId(id: 'MOVIE_1'),
            price: 14.42,
            total: 20,
            available: 20);

        $this->ticketRepository = M::mock(TicketRepository::class);
        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with('THEATER_1', 'MOVIE_1')
            ->andReturn($ticket)
            ->byDefault();

        $this->pricingPolicy = new DefaultPricingPolicy(ticketRepository: $this->ticketRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetPrice(): void
    {
        $price = $this->pricingPolicy->getPrice(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1');

        $this->assertEquals(14.42, $price);
    }

    #[Test]
    public function PriceNotFound(): void
    {
        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with('THEATER_1', 'MOVIE_1')
            ->andReturnNull();

        $price = $this->pricingPolicy->getPrice(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1');

        $this->assertNull($price);
    }
}