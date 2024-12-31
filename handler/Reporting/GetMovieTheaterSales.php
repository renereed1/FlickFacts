<?php

use DI\ContainerBuilder;
use FlickFacts\Reporting\Delivery\GetMovieTheaterSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$salesReadModel = $container->get(SalesReadModel::class);

return new GetMovieTheaterSalesHandler(salesReadModel: $salesReadModel);
