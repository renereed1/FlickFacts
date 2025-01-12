<?php declare(strict_types=1);

namespace FlickFacts\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Theater\ReadModel\TheaterReadModel;

class GetTheaterHandler extends HttpHandler
{
    public function __construct(private readonly TheaterReadModel $theaterReadModel)
    {

    }

    /**
     * Handles the HTTP request to retrieve theater details.
     *
     * @param HttpRequestEvent $event The HTTP request event.
     * @param Context $context The Bref context.
     *
     * @return HttpResponse The HTTP response containing theater details.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->theaterReadModel->findTheater(theaterId: $theaterId);

        return new HttpResponse(json_encode($response), ['Content-type' => 'application/json'],
            200);
    }
}