<?php

namespace FlickFacts\Theater\Sales\Infrastructure\Persistence\Repository;

use FlickFacts\Theater\Sales\Domain\Sales\Entity\Sales;
use FlickFacts\Theater\Sales\Domain\Sales\SalesRepository;
use PDO;

readonly class PostgresSalesRepository implements SalesRepository
{
    public function __construct(private PDO $pdo)
    {

    }

    public function createSale(Sales $sales): void
    {
        $sql = "INSERT INTO flickfacts.sales (id, theater_id, movie_id, price, quantity, created_at)
            VALUES (:id, :theater_id, :movie_id, :price, :quantity, :created_at)";
        $statement = $this->pdo->prepare($sql);

        $data = $sales->serialize();

        $statement->execute([
            ':id' => $data['id'],
            ':theater_id' => $data['theaterId'],
            ':movie_id' => $data['movieId'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':created_at' => $data['createdAt'],
        ]);
    }
}