<?php

namespace FlickFacts\Theater\Infrastructure\Persistence\Repository;

use FlickFacts\Theater\Domain\Theater\Entity\Theater;
use FlickFacts\Theater\Domain\Theater\TheaterRepository;
use PDO;

readonly class PostgresTheaterRepository implements TheaterRepository
{
    public function __construct(private PDO $pdo)
    {

    }

    public function createTheater(Theater $theater): void
    {
        $sql = "INSERT INTO flickfacts.theaters (id, created_at, name) VALUES (:id, :created_at, :name)";
        $statement = $this->pdo->prepare($sql);

        $data = $theater->serialize();

        $statement->execute([
            ':id' => $data['id'],
            ':created_at' => $data['createdAt'],
            ':name' => $data['name'],
        ]);
    }
}