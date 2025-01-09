<?php

namespace FlickFacts\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

class GetMovieTheaterSalesHandler extends HttpHandler
{
    public function __construct(private readonly SalesReadModel $salesReadModel)
    {

    }

    /**
     * Handles the HTTP request to get sales for a specific movie theater.
     *
     * @param HttpRequestEvent $event The incoming HTTP request event.
     * @param Context $context The Bref context.
     *
     * @return HttpResponse The HTTP response containing the sales data.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $movieId = $pathParameters['movieId'] ?? '';

        $response = $this->salesReadModel->findMovieTheaterSales(movieId: $movieId);

        return new HttpResponse(json_encode($response),
            ['Content-type' => 'application/json'],
            200);
    }
}