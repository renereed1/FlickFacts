<?php

use DI\ContainerBuilder;
use FlickFacts\Movie\Delivery\GetMoviesHandler;
use FlickFacts\Movie\ReadModel\MovieReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$movieReadModel = $container->get(MovieReadModel::class);

return new GetMoviesHandler(movieReadModel: $movieReadModel);