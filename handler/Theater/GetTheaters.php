<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Theater\Delivery\GetTheatersHandler;
use FlickFacts\Theater\ReadModel\TheaterReadModel;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$theaterReadModel = $container->get(TheaterReadModel::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new GetTheatersHandler(theaterReadModel: $theaterReadModel),
    $logger
);