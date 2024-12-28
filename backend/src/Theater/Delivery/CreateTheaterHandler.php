<?php

namespace FlickFacts\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheaterRequest;

class CreateTheaterHandler extends HttpHandler
{
    public function __construct(private readonly CreateTheater $createTheater)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $body = json_decode($event->getBody(), true);
        $name = $body['name'] ?? '';

        $request = new CreateTheaterRequest(name: $name);

        $this->createTheater->execute($request);

        return new HttpResponse(json_encode([
            'message' => 'Theater has been created',
        ]), ['Content-type' => 'application/json'],
            201);
    }
}