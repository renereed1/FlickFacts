<?php

namespace FlickFacts\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Movie\ReadModel\MovieReadModel;

class GetMovieHandler extends HttpHandler
{
    public function __construct(private readonly MovieReadModel $movieReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $movieId = $pathParameters['movieId'] ?? '';

        $response = $this->movieReadModel->findMovie($movieId);

        return new HttpResponse(json_encode($response),
            ['Content-type' => 'application/json'],
            200);
    }
}