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