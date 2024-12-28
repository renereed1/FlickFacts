<?php

use DI\ContainerBuilder;
use FlickFacts\Theater\Ticket\Delivery\CreateTicketHandler;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createTicket = $container->get(CreateTicket::class);

return new CreateTicketHandler(createTicket: $createTicket);