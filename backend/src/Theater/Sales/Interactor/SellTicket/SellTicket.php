<?php

namespace FlickFacts\Theater\Sales\Interactor\SellTicket;

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

class SellTicket
{
    public function __construct(private readonly IdGenerator     $idGenerator,
                                private readonly Clock           $clock,
                                private readonly SalesRepository $salesRepository,
                                private readonly TicketService   $ticketService,
                                private readonly PricingPolicy   $pricingPolicy,)
    {

    }

    /**
     * Executes the ticket selling process.
     *
     * @param SellTicketRequest $request The request containing ticket selling details.
     * @throws Exception If the process fails during ticket allocation or sale creation.
     */
    public function execute(SellTicketRequest $request): void
    {
        $price = $this->pricingPolicy->getPrice(theaterId: $request->theaterId,
            movieId: $request->movieId);

        $sale = $this->sellTicket(theaterId: $request->theaterId,
            movieId: $request->movieId,
            price: $price,
            quantity: $request->quantity);

        // Additional logic can be implemented based on the sale aggregate
    }

    /**
     * Handles the creation of a sale and ticket allocation.
     *
     * @param string $theaterId The ID of the theater.
     * @param string $movieId The ID of the movie.
     * @param float $price The price of the tickets.
     * @param int $quantity The number of tickets to sell.
     * @return Sales The created Sales entity.
     * @throws Exception If ticket allocation or sale creation fails.
     */
    private function sellTicket(string $theaterId,
                                string $movieId,
                                float  $price,
                                int    $quantity): Sales
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $this->ticketService->allocateTickets(theaterId: new TheaterId(id: $theaterId),
            movieId: new MovieId(id: $movieId),
            quantity: $quantity);

        $sale = new Sales(salesId: new SalesId($id),
            createdAt: $createdAt,
            theaterId: new TheaterId(id: $theaterId),
            movieId: new MovieId(id: $movieId),
            price: $price,
            quantity: $quantity);

        $this->createSale($sale);

        return $sale;
    }

    /**
     * Persists the sale in the repository.
     * If an exception occurs, releases the allocated tickets.
     *
     * @param Sales $sales The Sales entity to persist.
     * @throws Exception If persisting the sale fails.
     */
    private function createSale(Sales $sales): void
    {
        try {
            $this->salesRepository->createSale($sales);
        } catch (Exception $e) {
            $this->ticketService->releaseTickets(theaterId: $sales->theaterId,
                movieId: $sales->movieId,
                quantity: $sales->quantity);

            throw $e;
        }
    }
}