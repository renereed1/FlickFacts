<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Movie\Delivery\GetMovieHandler;
use FlickFacts\Movie\ReadModel\MovieReadModel;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$movieReadModel = $container->get(MovieReadModel::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new GetMovieHandler(movieReadModel: $movieReadModel),
    $logger
);