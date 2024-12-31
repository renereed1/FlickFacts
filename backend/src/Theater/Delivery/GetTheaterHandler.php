<?php

namespace FlickFacts\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\ReadModel\TheaterReadModel;

class GetTheaterHandler extends HttpHandler
{
    public function __construct(private readonly TheaterReadModel $theaterReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->theaterReadModel->findTheater(theaterId: $theaterId);

        return new HttpResponse(json_encode($response), ['Content-type' => 'application/json'],
            200);
    }
}