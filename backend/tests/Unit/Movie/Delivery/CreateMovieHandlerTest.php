<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Movie\Delivery\CreateMovieHandler;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovie;
use FlickFacts\Movie\Interactor\CreateMovie\CreateMovieRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateMovieHandlerTest extends TestCase
{
    private CreateMovieHandler $createMovieHandler;

    private CreateMovie $createMovie;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $request = new CreateMovieRequest(title: 'Movie 1',
            description: 'Description 1');

        $this->createMovie = M::mock(CreateMovie::class);
        $this->createMovie->expects('execute')
            ->with(M::on(function ($args) use ($request) {
                return $args == $request;
            }));

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            'body' => json_encode([
                'title' => 'Movie 1',
                'description' => 'Description 1',
            ]),
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->createMovieHandler = new CreateMovieHandler(createMovie: $this->createMovie);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanCreateMovie(): void
    {
        $response = $this->createMovieHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(201, $response->toApiGatewayFormatV2()['statusCode']);
    }
}