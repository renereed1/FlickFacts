<?php declare(strict_types=1);

namespace FlickFacts\Theater\Ticket\Infrastructure\Persistence\ReadModel;

use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use PDO;

readonly class PostgresTicketReadModel implements TicketReadModel
{
    public function __construct(private PDO $pdo)
    {

    }

    /**
     * Retrieves detailed information about available tickets for a given theater.
     *
     * @param string $theaterId The ID of the theater.
     *
     * @return array The ticket details, including:
     *               - `ticket_id`: The ticket ID.
     *               - `movie_id`: The ID of the movie.
     *               - `movie_title`: The title of the movie.
     *               - `price`: The price of the ticket.
     *               - `total`: The total number of tickets.
     *               - `available`: The number of available tickets.
     */
    public function findTickets(string $theaterId): array
    {
        $sql = 'SELECT t.id AS ticket_id, 
                   t.movie_id, 
                   m.title AS movie_title, 
                   t.price, 
                   t.total ,
                   t.available
            FROM flickfacts.tickets t
            INNER JOIN flickfacts.movies m ON t.movie_id = m.id
            WHERE t.theater_id = :theaterId
                AND t.available = (
                    SELECT MAX(available)
                    FROM flickfacts.tickets
                    WHERE movie_id = t.movie_id
                        AND theater_id = :theaterId)';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':theaterId', $theaterId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isTicketAvailable(string $theaterId,
                                      string $movieId): bool
    {
        $sql = '
                SELECT available
                FROM flickfacts.tickets
                WHERE theater_id = :theaterId
                    AND movie_id = :movieId
                    AND available > 0';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':theaterId', $theaterId, PDO::PARAM_STR);
        $statement->bindParam(':movieId', $movieId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->rowCount() >= 1;
    }
}