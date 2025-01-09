<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Theater\Ticket\Delivery\CreateTicketHandler;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createTicket = $container->get(CreateTicket::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new CreateTicketHandler(createTicket: $createTicket),
    $logger
);