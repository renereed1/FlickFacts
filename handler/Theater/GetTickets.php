<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Theater\Ticket\Delivery\GetTicketsHandler;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$ticketReadModel = $container->get(TicketReadModel::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new GetTicketsHandler(ticketReadModel: $ticketReadModel),
    $logger
);