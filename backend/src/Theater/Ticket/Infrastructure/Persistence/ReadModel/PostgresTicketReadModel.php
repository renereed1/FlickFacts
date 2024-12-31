<?php

namespace FlickFacts\Theater\Ticket\Infrastructure\Persistence\ReadModel;

use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use PDO;

readonly class PostgresTicketReadModel implements TicketReadModel
{
    public function __construct(private PDO $pdo)
    {

    }

    public function getTickets(string $theaterId): array
    {
        $sql = 'SELECT t.id AS ticket_id, 
                   t.movie_id, 
                   m.title AS movie_title, 
                   t.price, 
                   t.total ,
                   t.available
            FROM flickfacts.ticket t
            INNER JOIN flickfacts.movie m ON t.movie_id = m.id
            WHERE t.theater_id = :theaterId';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':theaterId', $theaterId, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}