<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Delivery\GetTheatersHandler;
use FlickFacts\Theater\ReadModel\TheaterReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTheatersHandlerTest extends TestCase
{
    private GetTheatersHandler $getTheatersHandler;

    private TheaterReadModel $theaterReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->theaterReadModel = M::mock(TheaterReadModel::class);
        $this->theaterReadModel->expects('findTheaters')
            ->andReturn([
                'id' => 'THEATER_1',
                'name' => 'Theater 1',
                'number_of_movies' => '1',
                'revenue' => '19.1922'
            ]);

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'GET',
                ]
            ],
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->getTheatersHandler = new GetTheatersHandler(theaterReadModel: $this->theaterReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetTheaters(): void
    {
        $response = $this->getTheatersHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);
        $this->assertEquals('THEATER_1', $body['id']);
        $this->assertEquals('Theater 1', $body['name']);
        $this->assertEquals(1, $body['number_of_movies']);
        $this->assertEquals('19.1922', $body['revenue']);
    }
}