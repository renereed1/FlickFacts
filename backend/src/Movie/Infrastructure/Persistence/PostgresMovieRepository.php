<?php

namespace FlickFacts\Movie\Infrastructure\Persistence;

use Exception;
use FlickFacts\Movie\Domain\Movie\Entity\Movie;
use FlickFacts\Movie\Domain\Movie\MovieRepository;
use PDO;

readonly class PostgresMovieRepository implements MovieRepository
{
    public function __construct(private PDO $pdo)
    {

    }

    public function createMovie(Movie $movie): void
    {
        $sql = "INSERT INTO flickfacts.movies (id, created_at, title, description) VALUES (:id, :created_at, :title, :description)";
        $statement = $this->pdo->prepare($sql);

        $data = $movie->serialize();

        try {
            $statement->execute([
                ':id' => $data['id'],
                ':created_at' => $data['createdAt'],
                ':title' => $data['title'],
                ':description' => $data['description'],
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}