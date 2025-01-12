<?php declare(strict_types=1);

namespace FlickFacts\Reporting\Infrastructure\Persistence\ReadModel;

use DateTimeInterface;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use PDO;

class PostgresSalesReadModel implements SalesReadModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Retrieves the theater with the highest sales on a specific date.
     *
     * @param DateTimeInterface $when The date for which sales data is to be fetched.
     * @return array|null Associative array with theater details or empty array if no data is found.
     */
    public function findTheaterWithHighestSalesByDate(DateTimeInterface $when): ?array
    {
        $date = $when->format('Y-m-d');


        $sql = '
            SELECT
                s.theater_id,
                t.name AS theater_name,
                SUM(s.price * s.quantity) AS total_sales
            FROM
                flickfacts.sales s
            JOIN
                flickfacts.theaters t ON s.theater_id = t.id
            WHERE
                s.created_at::date = :date
            GROUP BY
                s.theater_id, t.name
            ORDER BY
                total_sales DESC
            LIMIT 1
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    /**
     * Retrieves sales details for movies associated with a specific theater ID.
     *
     * @param string $theaterId The ID of the theater.
     * @return array Associative array of sales data for movies at the specified theater.
     */
    public function findTheaterMovieSales(string $theaterId): array
    {
        $sql = '
            SELECT
                s.movie_id as id,
                m.title as movie,
                s.price as price,
                s.quantity,
                s.discount,
                s.final_price
            FROM flickfacts.sales s
            LEFT JOIN flickfacts.movies m 
                on m.id = s.movie_id
            WHERE s.theater_id = :theaterId
            ORDER BY s.created_at::date DESC;';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':theaterId', $theaterId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves sales details for a specific movie across different theaters.
     *
     * @param string $movieId The ID of the movie.
     * @return array Associative array of sales data for the specified movie at various theaters.
     */
    public function findMovieTheaterSales(string $movieId): array
    {
        $sql = '
                SELECT t.name as theater,
                       m.title as movie,
                       s.price AS price,
                       SUM(s.quantity) AS tickets_sold,
                       SUM(s.final_price * s.quantity) AS total_revenue
                FROM flickfacts.sales s
                    LEFT JOIN flickfacts.theaters t ON t.id = s.theater_id
                    LEFT JOIN flickfacts.movies m ON m.id = s.movie_id
                WHERE m.id = :movieId
                GROUP BY t.name, m.title, s.price
                ORDER BY total_revenue DESC;';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':movieId', $movieId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}