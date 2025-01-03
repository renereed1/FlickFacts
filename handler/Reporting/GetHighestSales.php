<?php

use DI\ContainerBuilder;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Reporting\Delivery\GetHighestSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$clock = $container->get(Clock::class);
$salesReadModel = $container->get(SalesReadModel::class);

return new GetHighestSalesHandler(clock: $clock,
    salesReadModel: $salesReadModel);
