<?php

namespace FlickFacts\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

class GetHighestSalesHandler extends HttpHandler
{
    public function __construct(private readonly Clock          $clock,
                                private readonly SalesReadModel $salesReadModel)
    {

    }

    public function handleRequest(HttpRequestEvent $event, Context $context): HttpResponse
    {
        $when = $event->getQueryParameters()['when'] ??
            $this->clock->now()
                ->format(DateTimeInterface::ATOM);

        try {
            $date = new DateTimeImmutable($when);
        } catch (Exception) {
            return new HttpResponse(json_encode([
                    'error' => [
                        'message' => 'Invalid date format'
                    ]
                ]
            ), ['Content-type' => 'application/json'],
                400);
        }
        
        $response = $this->salesReadModel->findTheaterWithHighestSalesByDate($date);

        return new HttpResponse(json_encode($response),
            ['Content-type' => 'application/json'],
            200);
    }
}