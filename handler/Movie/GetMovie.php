<?php

use DI\ContainerBuilder;
use FlickFacts\Movie\Delivery\GetMovieHandler;
use FlickFacts\Movie\ReadModel\MovieReadModel;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$movieReadModel = $container->get(MovieReadModel::class);

return new GetMovieHandler(movieReadModel: $movieReadModel);