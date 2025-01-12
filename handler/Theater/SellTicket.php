<?php declare(strict_types=1);

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Theater\Sales\Delivery\SellTicketHandler;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$sellTicket = $container->get(SellTicket::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new SellTicketHandler(sellTicket: $sellTicket),
    $logger
);
