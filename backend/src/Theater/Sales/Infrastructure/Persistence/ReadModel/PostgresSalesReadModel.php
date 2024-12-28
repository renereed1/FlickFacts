<?php

namespace FlickFacts\Theater\Sales\Infrastructure\Persistence\ReadModel;

use DateTimeInterface;
use FlickFacts\Theater\Sales\ReadModel\SalesReadModel;
use PDO;

class PostgresSalesReadModel implements SalesReadModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findTheaterWithHighestSales(DateTimeInterface $when): array
    {
        $date = $when->format('Y-m-d');


        $sql = '
            SELECT
                s.theater_id,
                t.name AS theater_name,
                SUM(s.price * s.quantity) AS total_sales
            FROM
                flickfacts.sale s
            JOIN
                flickfacts.theater t ON s.theater_id = t.id
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
}