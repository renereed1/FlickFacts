<?php

namespace FlickFacts\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovieRequest;

class CreateMovieHandler extends HttpHandler
{
    public function __construct(private readonly CreateMovie $createMovie)
    {

    }

    public function handleRequest(HttpRequestEvent $event, Context $context): HttpResponse
    {
        $body = json_decode($event->getBody(), true);
        $title = $body['title'] ?? '';
        $description = $body['description'] ?? '';

        $request = new CreateMovieRequest(title: $title,
            description: $description);

        $this->createMovie->execute($request);

        return new HttpResponse(json_encode([
            'message' => 'Movie has been created',
        ]), ['content-type' => 'application/json'],
            201);
    }
}