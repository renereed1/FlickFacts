<?php

use DI\ContainerBuilder;
use FlickFacts\Theater\Sales\Delivery\SellTicketHandler;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$sellTicket = $container->get(SellTicket::class);

return new SellTicketHandler(sellTicket: $sellTicket);
