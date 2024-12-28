<?php

use DI\ContainerBuilder;
use FlickFacts\Theater\Delivery\CreateTheaterHandler;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$createTheater = $container->get(CreateTheater::class);

return new CreateTheaterHandler(createTheater: $createTheater);
