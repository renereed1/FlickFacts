<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Domain\Sales\Entity;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use FlickFacts\Common\Domain\Entity\AggregateRoot;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Price;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\SalesId;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\Quantity;

class Sales extends AggregateRoot
{
    protected function __construct(public readonly SalesId   $salesId,
                                   DateTimeImmutable         $createdAt,
                                   public readonly TheaterId $theaterId,
                                   public readonly MovieId   $movieId,
                                   private readonly Price    $price,
                                   public readonly Quantity  $quantity,
                                   public readonly Discount  $discount,
                                   private float             $finalPrice)
    {
        parent::__construct(id: $salesId->id,
            createdAt: $createdAt,
            version: 1);
    }

    /**
     * Factory method to create a new Sales entity.
     *
     * @param SalesId $saleId The unique identifier for the sale.
     * @param DateTimeImmutable $createdAt The timestamp when the sale was created.
     * @param TheaterId $theaterId The identifier for the theater where the sale occurred.
     * @param MovieId $movieId The identifier for the movie associated with the sale.
     * @param Price $price The price per unit of the ticket.
     * @param Quantity $quantity The number of tickets sold.
     * @param Discount $discount An optional discount applied to the sale.
     * @return self A new instance of the Sales entity with the final price calculated.
     */
    public static function create(SalesId           $saleId,
                                  DateTimeImmutable $createdAt,
                                  TheaterId         $theaterId,
                                  MovieId           $movieId,
                                  Price             $price,
                                  Quantity          $quantity,
                                  Discount          $discount): self
    {
        $sale = new self(salesId: $saleId,
            createdAt: $createdAt,
            theaterId: $theaterId,
            movieId: $movieId,
            price: $price,
            quantity: $quantity,
            discount: $discount,
            finalPrice: 0.00);

        $sale->calculateFinalPrice();

        return $sale;
    }

    /**
     * Calculates the final price for the sale, considering the price, quantity, and any applicable discount.
     *
     * @return void
     */
    private function calculateFinalPrice(): void
    {
        $totalPrice = $this->price->price * $this->quantity->quantity;
        $discountPrice = $totalPrice * $this->discount->percent;
        $this->finalPrice = $totalPrice - $discountPrice;
    }

    /**
     * Hydrates a Sales entity from an associative array of data.
     *
     * @param array $data An associative array containing the data to hydrate the Sales entity.
     *                    Keys expected:
     *                    - 'id' (string): The unique identifier for the sale.
     *                    - 'createdAt' (string): The creation timestamp in a valid date-time format.
     *                    - 'theaterId' (string): The identifier for the theater.
     *                    - 'movieId' (string): The identifier for the movie.
     *                    - 'price' (float): The price per unit of the ticket.
     *                    - 'quantity' (float): The number of tickets sold.
     *                    - 'discount' (mixed): The discount applied, if any.
     *                    - 'finalPrice' (float): The calculated final price of the sale.
     *
     * @return self A hydrated instance of the Sales entity.
     *
     * @throws Exception If any of the required data keys are missing or invalid.
     * @throws DateMalformedStringException
     */
    public static function hydrate(array $data): self
    {
        return new self(salesId: new SalesId($data['id']),
            createdAt: new DateTimeImmutable($data['createdAt']),
            theaterId: new TheaterId($data['theaterId']),
            movieId: new MovieId($data['movieId']),
            price: new Price($data['price']),
            quantity: new Quantity($data['quantity']),
            discount: new Discount($data['discount']),
            finalPrice: $data['finalPrice']);
    }

    /**
     * Serializes the Sales entity to an array.
     *
     * @return array Serialized representation of the Sales entity.
     */
    public function serialize(): array
    {
        return [
            'id' => $this->salesId->id,
            'theaterId' => $this->theaterId->id,
            'movieId' => $this->movieId->id,
            'price' => $this->price->price,
            'quantity' => $this->quantity,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'discount' => $this->discount->discount,
            'finalPrice' => $this->finalPrice,
        ];
    }
}