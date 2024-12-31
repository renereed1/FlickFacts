<?php

namespace FlickFacts\Theater\Ticket\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;

class GetTicketsHandler extends HttpHandler
{
    public function __construct(private readonly TicketReadModel $ticketReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->ticketReadModel->getTickets($theaterId);


        return new HttpResponse(json_encode($response),
            ['Content-Type' => 'application/json'],
            200);
    }
}