<?php declare(strict_types=1);

namespace FlickFacts\Movie\Interactor\CreateMovie;

use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Common\ApplicationService\IdGenerator\IdGenerator;
use FlickFacts\Movie\Domain\Movie\Entity\Movie;
use FlickFacts\Movie\Domain\Movie\MovieRepository;
use FlickFacts\Movie\Domain\Movie\ValueObject\Description;
use FlickFacts\Movie\Domain\Movie\ValueObject\MovieId;
use FlickFacts\Movie\Domain\Movie\ValueObject\Title;

class CreateMovie
{
    public function __construct(private readonly IdGenerator     $idGenerator,
                                private readonly Clock           $clock,
                                private readonly MovieRepository $movieRepository)
    {

    }

    /**
     * Handles the creation of a new Movie.
     *
     * @param CreateMovieRequest $request The request containing details to create the movie.
     *
     * @return void
     */
    public function execute(CreateMovieRequest $request): void
    {
        $movie = $this->createMovie(title: $request->title,
            description: $request->description);

        // Additional logic can be implemented based on the movie aggregate
    }

    /**
     * Creates a Movie entity from the provided title and description.
     *
     * @param string $title The title of the movie.
     * @param string $description The description of the movie.
     *
     * @return Movie The newly created Movie entity.
     */
    private function createMovie(string $title,
                                 string $description): Movie
    {
        $id = $this->idGenerator->nextId();
        $createdAt = $this->clock->now();

        $movie = new Movie(movieId: new MovieId(id: $id),
            createdAt: $createdAt,
            title: new Title(title: $title),
            description: new Description(description: $description));

        $this->movieRepository->createMovie($movie);

        return $movie;
    }
}