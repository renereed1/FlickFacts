<?php

namespace FlickFacts\Tests\Unit\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Reporting\Delivery\GetTheaterMovieSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTheaterMovieSalesHandlerTest extends TestCase
{
    private GetTheaterMovieSalesHandler $getTheaterMovieSalesHandler;

    private SalesReadModel $salesReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->salesReadModel = m::mock(SalesReadModel::class);
        $this->salesReadModel->expects('findTheaterMovieSales')
            ->andReturn([
                [
                    'id' => 'MOVIE_1',
                    'movie' => 'Movie 1',
                    'price' => 19.99,
                    'tickets_sold' => 1,
                    'total_revenue' => 19.99
                ]
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

        $this->getTheaterMovieSalesHandler = new GetTheaterMovieSalesHandler(salesReadModel: $this->salesReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetTheaterMovieSalesHandler(): void
    {
        $response = $this->getTheaterMovieSalesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);
        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertCount(1, $body);
        $this->assertEquals('Movie 1', $body[0]['movie']);
        $this->assertEquals(19.99, $body[0]['price']);
        $this->assertEquals(1, $body[0]['tickets_sold']);
        $this->assertEquals(19.99, $body[0]['total_revenue']);
    }
}