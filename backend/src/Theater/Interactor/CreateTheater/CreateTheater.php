<?php declare(strict_types=1);

namespace FlickFacts\Theater\Interactor\CreateTheater;

use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Theater\Domain\Theater\Entity\Theater;
use FlickFacts\Theater\Domain\Theater\TheaterRepository;
use FlickFacts\Theater\Domain\Theater\ValueObject\Name;
use FlickFacts\Theater\Domain\Theater\ValueObject\TheaterId;

class CreateTheater
{
    public function __construct(private readonly IdGenerator       $idGenerator,
                                private readonly Clock             $clock,
                                private readonly TheaterRepository $theaterRepository)
    {

    }

    /**
     * Executes the use case to create a new theater.
     *
     * @param CreateTheaterRequest $request The request containing data required to create a theater.
     * @return void
     */
    public function execute(CreateTheaterRequest $request): void
    {
        $theater = $this->createTheater(name: $request->name);

        // Additional logic can be implemented based on the theater aggregate
    }

    /**
     * Creates a new Theater entity.
     *
     * @param string $name The name of the theater to be created.
     * @return Theater The newly created Theater entity.
     */
    private function createTheater(string $name): Theater
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $theater = new Theater(theaterId: new TheaterId($id),
            createdAt: $createdAt,
            name: new Name($name));

        $this->theaterRepository->createTheater($theater);

        return $theater;
    }
}