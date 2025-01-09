<?php

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Theater\Delivery\CreateTheaterHandler;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createTheater = $container->get(CreateTheater::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new CreateTheaterHandler(createTheater: $createTheater),
    $logger
);
