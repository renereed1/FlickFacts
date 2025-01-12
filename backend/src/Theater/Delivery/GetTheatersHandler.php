<?php declare(strict_types=1);

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

    /**
     * Handles the HTTP request to retrieve a list of theaters.
     *
     * @param HttpRequestEvent $event The HTTP request event.
     * @param Context $context The Bref context.
     *
     * @return HttpResponse The HTTP response containing a list of theaters.
     */

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $theaters = $this->theaterReadModel->findTheaters();

        return new HttpResponse(json_encode($theaters), ['content-type' => 'application/json'],
            200);
    }
}