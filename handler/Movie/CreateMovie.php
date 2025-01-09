<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Movie\Delivery\CreateMovieHandler;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createMovie = $container->get(CreateMovie::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new CreateMovieHandler(createMovie: $createMovie),
    $logger
);