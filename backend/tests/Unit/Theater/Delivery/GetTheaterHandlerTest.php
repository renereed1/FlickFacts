<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Delivery\GetTheaterHandler;
use FlickFacts\Theater\ReadModel\TheaterReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTheaterHandlerTest extends TestCase
{
    private GetTheaterHandler $getTheaterHandler;

    private TheaterReadModel $theaterReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->theaterReadModel = M::mock(TheaterReadModel::class);
        $this->theaterReadModel->expects('findTheater')
            ->andReturn([
                'id' => 'THEATER_1'
            ]);

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'GET',
                ]
            ],
            "pathParameters" => [
                "theaterId" => "THEATER_1"
            ],
        ]);
        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->getTheaterHandler = new GetTheaterHandler(theaterReadModel: $this->theaterReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetTheater(): void
    {
        $response = $this->getTheaterHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
    }
}