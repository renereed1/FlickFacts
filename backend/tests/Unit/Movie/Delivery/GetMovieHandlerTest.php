<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Movie\Delivery\GetMovieHandler;
use FlickFacts\Movie\ReadModel\MovieReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetMovieHandlerTest extends TestCase
{
    private GetMovieHandler $getMovieHandler;

    private MovieReadModel $movieReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->movieReadModel = m::mock(MovieReadModel::class);
        $this->movieReadModel->expects('findMovie')
            ->with('MOVIE_1')
            ->andReturn([
                'id' => 'MOVIE_1',
                'name' => 'Movie 1',
            ]);

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            "pathParameters" => [
                "movieId" => "MOVIE_1"
            ],
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->getMovieHandler = new GetMovieHandler(movieReadModel: $this->movieReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetMovieHandler(): void
    {
        $response = $this->getMovieHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);
        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertEquals('MOVIE_1', $body['id']);
        $this->assertEquals('Movie 1', $body['name']);
    }
}