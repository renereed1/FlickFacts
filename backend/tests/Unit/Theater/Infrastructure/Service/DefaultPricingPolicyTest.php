<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Infrastructure\Service;

use DateTimeImmutable;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Infrastructure\Service\DefaultPricingPolicy;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
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
            price: new Price(price: 14.42),
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

        $this->assertEquals(14.42, $price->price);
    }

    #[Test]
    public function PriceNotFound(): void
    {
        $this->ticketRepository->expects('findTicketByTheaterIdAndMovieId')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                })
            )
            ->andReturnNull();

        $price = $this->pricingPolicy->getPrice(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1');

        $this->assertNull($price);
    }
}