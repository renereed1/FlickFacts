<?php

namespace FlickFacts\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\ReadModel\TheaterReadModel;

class GetTheatersHandler extends HttpHandler
{
    public function __construct(private readonly TheaterReadModel $theaterReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $theaters = $this->theaterReadModel->findTheaters();

        return new HttpResponse(json_encode($theaters), ['content-type' => 'application/json'],
            200);
    }
}