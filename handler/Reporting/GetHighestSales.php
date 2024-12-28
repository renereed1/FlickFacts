<?php

use DI\ContainerBuilder;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$getHighestSales = $container->get(GetHighestSales::class);

return new GetHighestSalesHandler(getHighestSales: $getHighestSales);
