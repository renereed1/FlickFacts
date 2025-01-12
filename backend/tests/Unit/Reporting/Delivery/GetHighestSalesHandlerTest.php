<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Reporting\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use DateTimeImmutable;
use FlickFacts\Common\ApplicationService\Clock\Clock;
use FlickFacts\Reporting\Delivery\GetHighestSalesHandler;
use FlickFacts\Reporting\ReadModel\SalesReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetHighestSalesHandlerTest extends TestCase
{
    private GetHighestSalesHandler $getHighestSalesHandler;

    private SalesReadModel $salesReadModel;

    private Clock $clock;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->salesReadModel = m::mock(SalesReadModel::class);
        $this->salesReadModel->expects('findTheaterWithHighestSalesByDate')
            ->with(M::on(function ($args) {
                return $args == new DateTimeImmutable('2003-11-13');
            }))
            ->andReturn([
                'theater_id' => 'THEATER_1',
                'theater_name' => 'THEATER 1',
                'total_sales' => 18.2600
            ])
            ->byDefault();

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'GET',
                ]
            ],
            'queryStringParameters' => [
                'when' => '2003-11-13',
            ],
        ]);

        $this->clock = M::mock(Clock::class);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->getHighestSalesHandler = new GetHighestSalesHandler(clock: $this->clock,
            salesReadModel: $this->salesReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetHighestSales(): void
    {
        $response = $this->getHighestSalesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertEquals('THEATER 1', $body['theater_name']);
        $this->assertEquals(18.2600, $body['total_sales']);
    }

    #[Test]
    public function CanDefaultToCurrentDate(): void
    {
        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            'queryStringParameters' => [
                // 'when' => '2003-11-13',
            ],
        ]);

        $this->salesReadModel->expects('findTheaterWithHighestSalesByDate')
            ->with(M::on(function ($args) {
                return $args == new DateTimeImmutable('2003-11-18');
            }))
            ->andReturn([
                'theater_id' => 'THEATER_1',
                'theater_name' => 'THEATER 1',
                'total_sales' => 18.2600
            ]);

        $this->clock->expects('now')
            ->andReturn(new DateTimeImmutable('2003-11-18'));

        $response = $this->getHighestSalesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
    }

    #[Test]
    public function CanHandleInvalidDate(): void
    {
        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            'queryStringParameters' => [
                'when' => 'invalid',
            ],
        ]);

        $this->salesReadModel->expects('findTheaterWithHighestSalesByDate')
            ->never();

        $this->clock->expects('now')
            ->never();

        $response = $this->getHighestSalesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);

        $this->assertEquals(400, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertEquals('Invalid date format', $body['error']['message']);
    }

    #[Test]
    public function CanHandleNoSales(): void
    {
        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            'queryStringParameters' => [
                'when' => '2021-03-23',
            ],
        ]);

        $this->salesReadModel->expects('findTheaterWithHighestSalesByDate')
            ->andReturnNull();

        $this->clock->expects('now')
            ->never();

        $response = $this->getHighestSalesHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertNull($body);
    }
}