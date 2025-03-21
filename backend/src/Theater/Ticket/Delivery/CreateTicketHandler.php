<?php declare(strict_types=1);

namespace FlickFacts\Theater\Ticket\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use Exception;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicketRequest;
use RuntimeException;

class CreateTicketHandler extends HttpHandler
{
    public function __construct(private readonly CreateTicket $createTicket)
    {

    }

    /**
     * Handles the HTTP request to create a new ticket.
     *
     * @param HttpRequestEvent $event The HTTP request event containing request details.
     * @param Context $context The AWS Lambda context for the request.
     *
     * @return HttpResponse The HTTP response indicating success or failure.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $body = json_decode($event->getBody(), true);
        $movieId = $body['movieId'] ?? '';
        $price = (float)$body['price'] ?? 0.0;
        $total = (int)$body['total'] ?? 0;

        $request = new CreateTicketRequest(theaterId: $theaterId,
            movieId: $movieId,
            price: $price,
            total: $total);

        try {
            $this->createTicket->execute($request);
        } catch (RuntimeException $e) {
            return new HttpResponse(json_encode([
                'error' => $e->getMessage(),
            ]), ['Content-type' => 'application/json'],
                400);
        } catch (Exception $e) {
            print 'Exception: ' . $e->getMessage() . "\n";

            return new HttpResponse('Internal Server Error',
                ['Content-Type' => 'text/plain'],
                500);
        }

        return new HttpResponse(json_encode([
            'message' => 'Ticket created',
        ]), ['content-type' => 'application/json'],
            201);
    }
}