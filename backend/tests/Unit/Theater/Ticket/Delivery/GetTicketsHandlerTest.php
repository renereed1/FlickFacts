<?php

namespace FlickFacts\Tests\Unit\Theater\Ticket\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Ticket\Delivery\GetTicketsHandler;
use FlickFacts\Theater\Ticket\ReadModel\TicketReadModel;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTicketsHandlerTest extends TestCase
{
    private GetTicketsHandler $getTicketsHandler;

    private TicketReadModel $ticketReadModel;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->ticketReadModel = M::mock(TicketReadModel::class);
        $this->ticketReadModel->expects('findTickets')
            ->andReturn([[
                'id' => 'TICKET_1'
            ]]);

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

        $this->getTicketsHandler = new GetTicketsHandler(ticketReadModel: $this->ticketReadModel);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanGetTickets(): void
    {
        $response = $this->getTicketsHandler->handleRequest(event: $this->event,
            context: $this->context);

        $body = json_decode($response->toApiGatewayFormatV2()['body'], true);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
        $this->assertCount(1, $body);
        $this->assertEquals('TICKET_1', $body[0]['id']);
    }
}