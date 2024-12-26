<?php

namespace FlickFacts\Tests\Unit\Theater\Interactor;

use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Domain\Theater\Entity\Theater;
use FlickFacts\Theater\Domain\Theater\ValueObject\Name;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheaterRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TheaterRepository;

class CreateTheaterTest extends TestCase
{
    private CreateTheater $createTheater;

    private IdGenerator $idGenerator;

    private TheaterRepository $theaterRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->idGenerator = M::mock(IdGenerator::class);
        $this->idGenerator->expects('nextId')
            ->andReturn('THEATER_1');

        $this->clock = M::mock(Clock::class);
        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('1999-02-14T04:05:43+00:00'));

        $theater = new Theater(theaterId: new TheaterId('THEATER_1'),
            createdAt: new DateTimeImmutable('1999-02-14T04:05:43+00:00'),
            name: new Name('Theater 1'));
        $this->theaterRepository = M::mock(TheaterRepository::class);
        $this->theaterRepository->expects('createTheater')
            ->with(M::on(function ($args) use ($theater) {
                return $args == $theater;
            }));

        $this->createTheater = new CreateTheater(idGenerator: $this->idGenerator,
            clock: $this->clock,
            theaterRepository: $this->theaterRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function CanCreateTheater(): void
    {
        $request = new CreateTheaterRequest(name: 'Theater 1');
        $this->createTheater->execute($request);
    }
}