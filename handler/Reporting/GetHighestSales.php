<?php

use DI\ContainerBuilder;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Reporting\Delivery\GetHighestSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$clock = $container->get(Clock::class);
$salesReadModel = $container->get(SalesReadModel::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new GetHighestSalesHandler(clock: $clock,
        salesReadModel: $salesReadModel),
    $logger
);
