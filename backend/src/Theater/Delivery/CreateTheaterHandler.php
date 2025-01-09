<?php

namespace FlickFacts\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use Exception;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheaterRequest;
use RuntimeException;

class CreateTheaterHandler extends HttpHandler
{
    public function __construct(private readonly CreateTheater $createTheater)
    {

    }

    /**
     * Handles the HTTP request to create a theater.
     *
     * @param HttpRequestEvent $event The HTTP request event.
     * @param Context $context The Bref context.
     *
     * @return HttpResponse The HTTP response.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $body = json_decode($event->getBody(), true);
        $name = $body['name'] ?? '';

        $request = new CreateTheaterRequest(name: $name);

        try {
            $this->createTheater->execute($request);
        } catch (RuntimeException $e) {
            return new HttpResponse(json_encode([
                'error' => $e->getMessage(),
            ]), ['Content-type' => 'application/json'],
                400);
        } catch (Exception) {
            return new HttpResponse(json_encode('Internal Server Error'),
                ['Content-type' => 'text/plain'],
                500);
        }

        return new HttpResponse(json_encode([
            'message' => 'Theater has been created',
        ]), ['Content-type' => 'application/json'],
            201);
    }
}