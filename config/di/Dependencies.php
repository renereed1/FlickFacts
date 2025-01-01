<?php

use DI\ContainerBuilder;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Common\Infrastructure\Clock\SystemClock;
use FlickFacts\Common\Infrastructure\IdGenerator\UuidIdGenerator;
use FlickFacts\Common\Infrastructure\Persistence\Dsql\Connection;
use FlickFacts\Movie\Domain\Movie\MovieRepository;
use FlickFacts\Movie\Infrastructure\Persistence\PostgresMovieRepository;
use FlickFacts\Movie\Infrastructure\ReadModel\PostgresMovieReadModel;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use FlickFacts\Movie\ReadModel\MovieReadModel;
use FlickFacts\Reporting\Infrastructure\Persistence\ReadModel\PostgresSalesReadModel;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use FlickFacts\Theater\Application\Service\PricingPolicy;
use FlickFacts\Theater\Application\Service\TicketService;
use FlickFacts\Theater\Domain\Theater\TheaterRepository;
use FlickFacts\Theater\Infrastructure\Persistence\ReadModel\PostgresTheaterReadModel;
use FlickFacts\Theater\Infrastructure\Persistence\Repository\PostgresTheaterRepository;
use FlickFacts\Theater\Infrastructure\Service\DefaultPricingPolicy;
use FlickFacts\Theater\Infrastructure\Service\DefaultTicketService;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use FlickFacts\Theater\ReadModel\TheaterReadModel;
use FlickFacts\Theater\Sales\Domain\Sales\SalesRepository;
use FlickFacts\Theater\Sales\Infrastructure\Persistence\Repository\PostgresSalesRepository;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;
use FlickFacts\Theater\Ticket\Domain\Ticket\TicketRepository;
use FlickFacts\Theater\Ticket\Infrastructure\Persistence\ReadModel\PostgresTicketReadModel;
use FlickFacts\Theater\Ticket\Infrastructure\Persistence\Repository\PostgresTicketRepository;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $container) {

    $container->addDefinitions([

        Connection::class => function (ContainerInterface $container) {
            $connection = new Connection('', '');
            return $connection->getConnection(
                getenv('DB_HOST') ?: '',
                getenv('DB_USERNAME') ?: '',
                getenv('DB_DATABASE') ?: '',
                getenv('DB_PORT') ?: '',
                'us-east-2');
        },

        TheaterRepository::class => function (ContainerInterface $container) {
            return new PostgresTheaterRepository($container->get(Connection::class));
        },

        MovieRepository::class => function (ContainerInterface $container) {
            return new PostgresMovieRepository($container->get(Connection::class));
        },

        TicketRepository::class => function (ContainerInterface $container) {
            return new PostgresTicketRepository($container->get(Connection::class));
        },

        SalesRepository::class => function (ContainerInterface $container) {
            return new PostgresSalesRepository($container->get(Connection::class));
        },

        IdGenerator::class => function (ContainerInterface $container) {
            return new UuidIdGenerator();
        },

        Clock::class => function (ContainerInterface $container) {
            return new SystemClock();
        },

        TicketService::class => function (ContainerInterface $container) {
            return new DefaultTicketService($container->get(TicketRepository::class));
        },

        PricingPolicy::class => function (ContainerInterface $container) {
            return new DefaultPricingPolicy($container->get(TicketRepository::class));
        },

        CreateTheater::class => function (ContainerInterface $container) {
            return new CreateTheater(idGenerator: $container->get(IdGenerator::class),
                clock: $container->get(SystemClock::class),
                theaterRepository: $container->get(TheaterRepository::class));
        },

        CreateMovie::class => function (ContainerInterface $container) {
            return new CreateMovie(idGenerator: $container->get(IdGenerator::class),
                clock: $container->get(SystemClock::class),
                movieRepository: $container->get(MovieRepository::class));
        },

        CreateTicket::class => function (ContainerInterface $container) {
            return new CreateTicket(idGenerator: $container->get(IdGenerator::class),
                clock: $container->get(SystemClock::class),
                ticketRepository: $container->get(TicketRepository::class),
                ticketReadModel: $container->get(TicketReadModel::class));
        },

        SellTicket::class => function (ContainerInterface $container) {
            return new SellTicket(idGenerator: $container->get(IdGenerator::class),
                clock: $container->get(SystemClock::class),
                salesRepository: $container->get(SalesRepository::class),
                ticketService: $container->get(TicketService::class),
                pricingPolicy: $container->get(PricingPolicy::class));
        },

        SalesReadModel::class => function (ContainerInterface $container) {
            return new PostgresSalesReadModel(pdo: $container->get(Connection::class));
        },

        TicketReadModel::class => function (ContainerInterface $container) {
            return new PostgresTicketReadModel(pdo: $container->get(Connection::class));
        },

        TheaterReadModel::class => function (ContainerInterface $container) {
            return new PostgresTheaterReadModel(pdo: $container->get(Connection::class));
        },

        MovieReadModel::class => function (ContainerInterface $container) {
            return new PostgresMovieReadModel(pdo: $container->get(Connection::class));
        },
    ]);
};
