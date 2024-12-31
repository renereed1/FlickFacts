<?php

namespace FlickFacts\Tests\Unit\Movie\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Movie\Delivery\GetMoviesHandler;
use FlickFacts\Movie\ReadModel\MovieReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetMoviesHandlerTest extends TestCase
{
    private GetMoviesHandler $getMoviesHandler;

    private MovieReadModel $movieReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->movieReadModel = m::mock(MovieReadModel::class);
        $this->movieReadModel->expects('getMovies')
            ->andReturn([
                [
                    'id' => 'MOVIE_1',
                    'title' => 'Movie 1',
                    'description' => 'Description 1'
                ]
            ]);

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'GET',
                ]
            ]
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->getMoviesHandler = new GetMoviesHandler(movieReadModel: $this->movieReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetMovies(): void
    {
        $response = $this->getMoviesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertCount(1, $body);
        $this->assertEquals('MOVIE_1', $body[0]['id']);
        $this->assertEquals('Movie 1', $body[0]['title']);
        $this->assertEquals('Description 1', $body[0]['description']);
    }
}