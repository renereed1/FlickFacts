<?php declare(strict_types=1);

namespace FlickFacts\Common\MiddleWare;

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use Monolog\Logger as MonologLogger;

class Logger extends HttpHandler
{
    public function __construct(private readonly HttpHandler   $nextHandler,
                                private readonly MonologLogger $logger)
    {

    }

    public function handleRequest(HttpRequestEvent $event,
                                  Context          $context): HttpResponse
    {
        // Extract information to log
        $method = $event->getMethod();
        $requestContext = $event->getRequestContext();
        $path = $event->getPath();
        $queryParams = $event->getQueryString();
        $body = json_decode($event->toArray()['body'] ?? '{}', true);
        $traceId = $context->getTraceId();

        // Log the incoming request
        $this->logger->info('Incoming request', [
            'method' => $method,
            'path' => $path,
            'query' => $queryParams,
            'body' => $body,
            'traceId' => $traceId,
            'ipAddress' => $requestContext['http']['sourceIp'],
        ]);

        // Delegate the request to the next handler
        $response = $this->nextHandler->handleRequest($event, $context);

        $responseV2 = $response->toApiGatewayFormatV2();

        // Log the response
        $this->logger->info('Response sent', [
            'statusCode' => $responseV2['statusCode'],
            'body' => $responseV2['body'],
            'traceId' => $traceId,
        ]);

        return $response;
    }
}