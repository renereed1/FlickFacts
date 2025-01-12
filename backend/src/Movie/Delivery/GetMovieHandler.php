<?php declare(strict_types=1);

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

    /**
     * Handles the HTTP request to fetch a movie by its ID.
     *
     * @param HttpRequestEvent $event The HTTP request event containing request details.
     * @param Context $context The AWS Lambda context for the request.
     *
     * @return HttpResponse The HTTP response containing movie details or an empty object if not found.
     */
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