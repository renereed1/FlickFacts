<?php declare(strict_types=1);

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

    /**
     * Handles the HTTP request to fetch a list of movies.
     *
     * @param HttpRequestEvent $event The HTTP request event containing request details.
     * @param Context $context The AWS Lambda context for the request.
     *
     * @return HttpResponse The HTTP response containing a list of movies in JSON format.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $response = $this->movieReadModel->findMovies();

        return new HttpResponse(json_encode($response),
            ['Content-Type' => 'application/json'],
            200);
    }
}