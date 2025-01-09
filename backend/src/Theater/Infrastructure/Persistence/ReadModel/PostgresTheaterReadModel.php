<?php

namespace FlickFacts\Theater\Infrastructure\Persistence\ReadModel;

use FlickFacts\Theater\ReadModel\TheaterReadModel;
use PDO;

class PostgresTheaterReadModel implements TheaterReadModel
{
    public function __construct(private PDO $pdo)
    {

    }

    /**
     * Retrieves all theaters along with their revenue and total revenue.
     *
     * @return array An array of theaters with associated revenue data.
     */
    public function findTheaters(): array
    {
        $sql = "
        -- Theaters revenue
        SELECT t.id, 
               t.name AS name,
               SUM(s.price * s.quantity) AS revenue,
               NULL AS total_revenue
        FROM flickfacts.theaters t
        LEFT JOIN flickfacts.sales s ON t.id = s.theater_id
        GROUP BY t.name, t.id

        UNION ALL

        -- Grand total revenue (only one row for total)
        SELECT NULL AS id, 
               'Total' AS name,
               SUM(s.price * s.quantity) AS revenue,
               SUM(s.price * s.quantity) AS total_revenue
        FROM flickfacts.sales s
        ORDER BY revenue DESC NULLS LAST;
        ";

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Retrieves a specific theater by its ID.
     *
     * @param string $theaterId The ID of the theater.
     *
     * @return array An associative array containing theater details.
     */
    public function findTheater(string $theaterId): array
    {
        $sql = 'SELECT id, name FROM flickfacts.theaters WHERE id = :theaterId';

        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(':theaterId', $theaterId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}