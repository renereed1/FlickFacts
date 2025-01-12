<?php declare(strict_types=1);

namespace FlickFacts\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

class GetTheaterMovieSalesHandler extends HttpHandler
{
    public function __construct(private readonly SalesReadModel $salesReadModel)
    {

    }

    /**
     * Handles the HTTP request to get sales for a specific theater and its movies.
     *
     * @param HttpRequestEvent $event The incoming HTTP request event.
     * @param Context $context The Bref context.
     *
     * @return HttpResponse The HTTP response containing the sales data.
     */
    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        $pathParameters = $event->getPathParameters();
        $theaterId = $pathParameters['theaterId'] ?? '';

        $response = $this->salesReadModel->findTheaterMovieSales(theaterId: $theaterId);

        return new HttpResponse(json_encode($response),
            ['content-type' => 'application/json'],
            200);
    }
}