<?php

namespace FlickFacts\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use Exception;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovieRequest;
use RuntimeException;

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

        try {
            $this->createMovie->execute($request);
        } catch (RuntimeException $e) {
            return new HttpResponse(json_encode([
                'error' => $e->getMessage()
            ]), ['Content-type' => 'application/json'],
                400);
        } catch (Exception $e) {
            print 'Exception: ' . $e->getMessage() . "\n";
            
            return new HttpResponse('Internal Server Error',
                ['Content-type' => 'text/plain'],
                500);
        }

        return new HttpResponse(json_encode([
            'message' => 'Movie has been created',
        ]), ['content-type' => 'application/json'],
            201);
    }
}