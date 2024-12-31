<?php

namespace FlickFacts\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

class GetTheaterMovieSalesHandler extends HttpHandler
{
    public function __construct(private readonly SalesReadModel $salesReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->salesReadModel->findTheaterMovieSales(theaterId: $theaterId);

        return new HttpResponse(json_encode($response),
            ['content-type' => 'application/json'],
            200);
    }
}