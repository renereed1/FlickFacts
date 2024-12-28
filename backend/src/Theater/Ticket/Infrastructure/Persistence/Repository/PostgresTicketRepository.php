<?php

namespace FlickFacts\Theater\Ticket\Infrastructure\Persistence\Repository;

use DateTimeImmutable;
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

    public function createTicket(Ticket $ticket): void
    {
        $sql = "INSERT INTO flickfacts.ticket (id, created_at, theater_id, movie_id, price, total, available) 
                  VALUES (:id, :created_at, :theater_id, :movie_id, :price, :total, :available)";

        $statement = $this->pdo->prepare($sql);

        $data = $ticket->serialize();

        $statement->execute([
            'id' => $data['id'],
            'created_at' => $data['createdAt'],
            'theater_id' => $data['theaterId'],
            'movie_id' => $data['movieId'],
            'price' => $data['price'],
            'total' => $data['total'],
            'available' => $data['available']
        ]);
    }

    public function save(Ticket $ticket): void
    {
        $sql = "UPDATE flickfacts.ticket 
                  SET available = :available 
                  WHERE id = :id";
        $statement = $this->pdo->prepare($sql);

        $data = $ticket->serialize();

        $statement->execute([
            'id' => $data['id'],
            'available' => $data['available'],
        ]);
    }

    public function findTicketByTheaterIdAndMovieId(TheaterId $theaterId,
                                                    MovieId   $movieId): ?Ticket
    {
        $sql = "SELECT * FROM flickfacts.ticket WHERE theater_id = :theaterId AND movie_id = :movieId LIMIT 1";
        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'theaterId' => $theaterId->id,
            'movieId' => $movieId->id,
        ]);

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