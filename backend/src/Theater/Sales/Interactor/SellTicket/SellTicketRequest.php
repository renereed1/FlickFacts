<?php declare(strict_types=1);

namespace FlickFacts\Theater\Sales\Interactor\SellTicket;

readonly class SellTicketRequest
{
    public function __construct(public string $theaterId,
                                public string $movieId,
                                public int    $quantity,
                                public string $discountCode)
    {

    }
}