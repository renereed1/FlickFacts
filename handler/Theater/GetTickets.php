<?php

use DI\ContainerBuilder;
use FlickFacts\Theater\Ticket\Delivery\GetTicketsHandler;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$ticketReadModel = $container->get(TicketReadModel::class);

return new GetTicketsHandler(ticketReadModel: $ticketReadModel);