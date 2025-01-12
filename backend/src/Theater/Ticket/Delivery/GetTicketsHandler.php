<?php declare(strict_types=1);

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

    /**
     * Handles the HTTP request to fetch a list of tickets.
     *
     * @param HttpRequestEvent $event The HTTP request event containing request details.
     * @param Context $context The AWS Lambda context for the request.
     *
     * @return HttpResponse The HTTP response containing a list of movies in JSON format.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->ticketReadModel->findTickets($theaterId);


        return new HttpResponse(json_encode($response),
            ['Content-Type' => 'application/json'],
            200);
    }
}