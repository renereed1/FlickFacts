<?php

namespace FlickFacts\Movie\Infrastructure\ReadModel;

use FlickFacts\Movie\ReadModel\MovieReadModel;
use PDO;

class PostgresMovieReadModel implements MovieReadModel
{
    public function __construct(private PDO $pdo)
    {

    }

    public function getMovies(): array
    {
        $sql = '
                SELECT
                    m.id,
                    m.title as title,
                    SUM(quantity) AS quantity,
                    SUM(price * quantity) AS total_revenue
                FROM flickfacts.sale s
                LEFT JOIN flickfacts.movie m ON s.movie_id = m.id
                GROUP BY movie_id, m.title, m.id;
                ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function findMovie(string $movieId): array
    {
        $sql = 'SELECT id, title, description from flickfacts.movie WHERE id = :movieId';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':movieId', $movieId);

        $statement->execute();

        return $statement->fetch();
    }
}