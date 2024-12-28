<?php

namespace FlickFacts\Theater\Ticket\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicketRequest;

class CreateTicketHandler extends HttpHandler
{
    public function __construct(private readonly CreateTicket $createTicket)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $body = json_decode($event->getBody(), true);
        $movieId = $body['movieId'] ?? '';
        $price = (float)$body['price'] ?? 0.0;
        $total = $body['total'] ?? '';

        $request = new CreateTicketRequest(theaterId: $theaterId,
            movieId: $movieId,
            price: $price,
            total: $total);

        $this->createTicket->execute($request);

        return new HttpResponse(json_encode([
            'message' => 'Ticket created',
        ]), ['content-type' => 'application/json'],
            201);
    }
}