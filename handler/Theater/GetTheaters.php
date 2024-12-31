<?php

use DI\ContainerBuilder;
use FlickFacts\Theater\Delivery\GetTheatersHandler;
use FlickFacts\Theater\ReadModel\TheaterReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$theaterReadModel = $container->get(TheaterReadModel::class);

return new GetTheatersHandler(theaterReadModel: $theaterReadModel);