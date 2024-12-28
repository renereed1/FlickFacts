<?php

namespace FlickFacts\Tests\Unit\Theater\Sales\Interactor;

use DateTimeImmutable;
use Exception;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Application\Service\PricingPolicy;
use FlickFacts\Theater\Application\Service\TicketService;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\Entity\Sales;
use FlickFacts\Theater\Sales\Domain\Sales\SalesRepository;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\SalesId;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicketRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SellTicketTest extends TestCase
{
    private SellTicket $sellTicket;

    private IdGenerator $idGenerator;

    private Clock $clock;

    private TicketService $ticketService;

    private PricingPolicy $pricingPolicy;

    private SalesRepository $salesRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->idGenerator = M::mock(IdGenerator::class);
        $this->idGenerator->expects('nextId')
            ->andReturn('SALES_1');

        $this->clock = M::mock(Clock::class);
        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('2016-10-13T03:03:23+00:00'));

        $sales = new Sales(salesId: new SalesId('SALES_1'),
            createdAt: new DateTimeImmutable('2016-10-13T03:03:23+00:00'),
            theaterId: new TheaterId('THEATER_1'),
            movieId: new MovieId('MOVIE_1'),
            price: 12.34,
            quantity: 1);

        $this->salesRepository = M::mock(SalesRepository::class);
        $this->salesRepository->expects('createSale')
            ->with(M::on(function ($args) use ($sales) {
                return $args == $sales;
            }))
            ->byDefault();

        $this->ticketService = M::mock(TicketService::class);
        $this->ticketService->expects('allocateTickets')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                }),
                1
            );


        $this->pricingPolicy = M::mock(PricingPolicy::class);
        $this->pricingPolicy->expects('getPrice')
            ->with('THEATER_1', 'MOVIE_1')
            ->andReturn(12.34);

        $this->sellTicket = new SellTicket(idGenerator: $this->idGenerator,
            clock: $this->clock,
            salesRepository: $this->salesRepository,
            ticketService: $this->ticketService,
            pricingPolicy: $this->pricingPolicy);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CanSellTicket(): void
    {
        $request = new SellTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            quantity: 1);

        $this->sellTicket->execute($request);
    }

    #[Test]
    public function CanRollbackSoldTickets(): void
    {
        $this->expectException(Exception::class);

        $this->salesRepository->expects('createSale')
            ->andThrows(new Exception());

        $this->ticketService->expects('releaseTickets')
            ->with(
                M::on(function ($theaterId) {
                    return $theaterId instanceof TheaterId && $theaterId->id === 'THEATER_1';
                }),
                M::on(function ($movieId) {
                    return $movieId instanceof MovieId && $movieId->id === 'MOVIE_1';
                }),
                1
            );


        $request = new SellTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            quantity: 1);

        $this->sellTicket->execute($request);
    }
}