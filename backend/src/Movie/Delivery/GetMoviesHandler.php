<?php

namespace FlickFacts\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Movie\ReadModel\MovieReadModel;

class GetMoviesHandler extends HttpHandler
{
    public function __construct(private readonly MovieReadModel $movieReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $response = $this->movieReadModel->findMovies();

        return new HttpResponse(json_encode($response),
            ['Content-Type' => 'application/json'],
            200);
    }
}