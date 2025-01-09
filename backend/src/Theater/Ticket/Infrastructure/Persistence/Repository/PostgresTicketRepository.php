<?php

namespace FlickFacts\Theater\Ticket\Infrastructure\Persistence\Repository;

use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use FlickFacts\Theater\Domain\Theater\ValueObject\MovieId;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Ticket\Domain\Ticket\Entity\Ticket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Domain\Ticket\ValueObject\TicketId;
use PDO;

class PostgresTicketRepository implements TicketRepository
{
    public function __construct(private PDO $pdo)
    {

    }

    /**
     * Retrieves a ticket by its associated theater ID and movie ID.
     *
     * @param Ticket $ticket
     * @return void The Ticket object if found, or null if no ticket matches.
     * @throws Exception
     */
    public function createTicket(Ticket $ticket): void
    {
        $sql = "INSERT INTO flickfacts.tickets (id, created_at, theater_id, movie_id, price, total, available) 
                  VALUES (:id, :created_at, :theater_id, :movie_id, :price, :total, :available)";

        $statement = $this->pdo->prepare($sql);

        $data = $ticket->serialize();

        try {
            $statement->execute([
                'id' => $data['id'],
                'created_at' => $data['createdAt'],
                'theater_id' => $data['theaterId'],
                'movie_id' => $data['movieId'],
                'price' => $data['price'],
                'total' => $data['total'],
                'available' => $data['available']
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Updates the availability of an existing Ticket in the repository.
     *
     * @param Ticket $ticket The Ticket entity with updated availability.
     * @return void
     * @throws Exception
     */
    public function save(Ticket $ticket): void
    {
        $sql = "UPDATE flickfacts.tickets 
                  SET available = :available 
                  WHERE id = :id";
        $statement = $this->pdo->prepare($sql);

        $data = $ticket->serialize();

        try {
            $statement->execute([
                'id' => $data['id'],
                'available' => $data['available'],
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Retrieves a ticket by its associated theater ID and movie ID.
     *
     * @param TheaterId $theaterId The ID of the theater.
     *
     * @return Ticket|null The Ticket object if found, or null if no ticket matches.
     * @throws DateMalformedStringException
     */
    public function findTicketByTheaterIdAndMovieId(TheaterId $theaterId,
                                                    MovieId   $movieId): ?Ticket
    {
        $sql = '
                SELECT *
                FROM flickfacts.tickets
                WHERE theater_id = :theaterId
                  AND movie_id = :movieId
                  AND (
                      -- Case 1: If there is only one ticket, allow it even if available = 0
                      (SELECT COUNT(*) FROM flickfacts.tickets WHERE theater_id = :theaterId AND movie_id = :movieId) = 1
                      -- Case 2: If there is more than one ticket, only select the one with available > 0
                      OR available > 0)
                LIMIT 1;';
        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute([
                'theaterId' => $theaterId->id,
                'movieId' => $movieId->id,
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Ticket(
            ticketId: new TicketId($row['id']),
            createdAt: new DateTimeImmutable($row['created_at']),
            theaterId: new TheaterId($row['theater_id']),
            movieId: new MovieId($row['movie_id']),
            price: (float)$row['price'],
            total: (int)$row['total'],
            available: (int)$row['available']
        );
    }
}