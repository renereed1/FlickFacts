<?php

namespace FlickFacts\Theater\Sales\Infrastructure\Persistence\Repository;

use Exception;
use FlickFacts\Theater\Sales\Domain\Sales\Entity\Sales;
use FlickFacts\Theater\Sales\Domain\Sales\SalesRepository;
use PDO;

readonly class PostgresSalesRepository implements SalesRepository
{
    public function __construct(private PDO $pdo)
    {

    }

    /**
     * Persists a sales entity in the PostgreSQL database.
     *
     * @param Sales $sales The sales entity to be stored.
     *
     * @throws Exception If the query execution fails.
     */
    public function createSale(Sales $sales): void
    {
        $sql = "INSERT INTO flickfacts.sales (id, theater_id, movie_id, price, quantity, created_at, discount, final_price)
            VALUES (:id, :theater_id, :movie_id, :price, :quantity, :created_at, :discount, :final_price)";
        $statement = $this->pdo->prepare($sql);

        $data = $sales->serialize();

        print_r($data);

        $statement->execute([
            ':id' => $data['id'],
            ':theater_id' => $data['theaterId'],
            ':movie_id' => $data['movieId'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':created_at' => $data['createdAt'],
            ':discount' => $data['discount'],
            ':final_price' => $data['finalPrice'],
        ]);
    }
}