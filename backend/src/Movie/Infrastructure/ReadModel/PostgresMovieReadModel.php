<?php declare(strict_types=1);

namespace FlickFacts\Movie\Infrastructure\ReadModel;

use FlickFacts\Movie\ReadModel\MovieReadModel;
use PDO;

class PostgresMovieReadModel implements MovieReadModel
{
    public function __construct(private PDO $pdo)
    {

    }

    /**
     * Retrieves a list of movies with their total revenue and sales quantities.
     *
     * @return array The list of movies, including:
     *               - `id`: The movie ID.
     *               - `title`: The movie title.
     *               - `quantity`: The total quantity of tickets sold.
     *               - `total_revenue`: The total revenue generated from sales.
     */
    public function findMovies(): array
    {
        $sql = '
                SELECT
                    m.id,
                    m.title as title,
                    COALESCE(SUM(s.quantity), 0) AS quantity,
                    COALESCE(SUM(s.price * s.quantity), 0.0) AS total_revenue
                FROM flickfacts.movies m
                    LEFT JOIN flickfacts.sales s on s.movie_id = m.id
                GROUP BY movie_id, m.title, m.id
                ORDER BY total_revenue DESC;
                ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Retrieves detailed information about a specific movie by its ID.
     *
     * @param string $movieId The ID of the movie to find.
     *
     * @return array The movie details, including:
     *               - `id`: The movie ID.
     *               - `title`: The movie title.
     *               - `description`: The movie description.
     */
    public function findMovie(string $movieId): array
    {
        $sql = 'SELECT id, title, description from flickfacts.movies WHERE id = :movieId';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':movieId', $movieId);

        $statement->execute();

        return $statement->fetch();
    }
}