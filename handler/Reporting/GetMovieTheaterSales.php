<?php declare(strict_types=1);

use DI\ContainerBuilder;
use FlickFacts\Common\MiddleWare\Logger;
use FlickFacts\Reporting\Delivery\GetMovieTheaterSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use Monolog\Logger as MonologLogger;

require __DIR__ . '/../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../../config/di/Dependencies.php')($containerBuilder);

$container = $containerBuilder
    ->build();

$salesReadModel = $container->get(SalesReadModel::class);
$logger = $container->get(MonologLogger::class);

return new Logger(
    new GetMovieTheaterSalesHandler(salesReadModel: $salesReadModel),
    $logger
);
