<?php

namespace FlickFacts\Tests\Unit\Movie\Interactor;

use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Movie\Domain\Movie\Entity\Movie;
use FlickFacts\Movie\Domain\Movie\MovieRepository;
use FlickFacts\Movie\Domain\Movie\ValueObject\Description;
use FlickFacts\Movie\Domain\Movie\ValueObject\MovieId;
use FlickFacts\Movie\Domain\Movie\ValueObject\Title;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovieRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateMovieTest extends TestCase
{
    private CreateMovie $createMovie;

    private IdGenerator $idGenerator;

    private Clock $clock;

    public function setUp(): void
    {
        parent::setUp();

        $this->idGenerator = M::mock(IdGenerator::class);
        $this->idGenerator->expects('nextId')
            ->andReturn('MOVIE_1')
            ->byDefault();

        $this->clock = M::mock(Clock::class);
        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('1984-11-23T10:03:23+00:00'));

        $movie = new Movie(movieId: new MovieId('MOVIE_1'),
            createdAt: new DateTimeImmutable('1984-11-23T10:03:23+00:00'),
            title: new Title('Title 1'),
            description: new Description('Description 1'));
        $this->movieRepository = M::mock(MovieRepository::class);
        $this->movieRepository->expects('createMovie')
            ->with(M::on(function ($args) use ($movie) {
                return $args == $movie;
            }));

        $this->createMovie = new CreateMovie(idGenerator: $this->idGenerator,
            clock: $this->clock,
            movieRepository: $this->movieRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CanCreateMovie(): void
    {
        $request = new CreateMovieRequest(title: 'Title 1',
            description: 'Description 1');

        $this->createMovie->execute($request);
    }
}