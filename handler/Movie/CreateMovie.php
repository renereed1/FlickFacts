<?php

use DI\ContainerBuilder;
use FlickFacts\Movie\Delivery\CreateMovieHandler;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createMovie = $container->get(CreateMovie::class);

return new CreateMovieHandler(createMovie: $createMovie);