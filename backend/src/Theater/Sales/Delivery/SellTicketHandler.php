<?php

namespace FlickFacts\Theater\Sales\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicketRequest;

class SellTicketHandler extends HttpHandler
{
    public function __construct(private readonly SellTicket $sellTicket)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $body = json_decode($event->getBody(), true);
        $movieId = $body['movieId'] ?? '';
        $quantity = (int)$body['quantity'] ?? 0;

        $request = new SellTicketRequest(theaterId: $theaterId,
            movieId: $movieId,
            quantity: $quantity);

        $this->sellTicket->execute($request);

        return new HttpResponse(json_encode([
            'message' => 'Ticket sold',
        ]), ['content-type' => 'application/json'],
            200);
    }
}