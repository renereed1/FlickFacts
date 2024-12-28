<?php

namespace FlickFacts\Tests\Unit\Theater\Ticket\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Ticket\Delivery\CreateTicketHandler;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicket;
use FlickFacts\Theater\Ticket\Interactor\CreateTicket\CreateTicketRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateTicketHandlerTest extends TestCase
{
    private CreateTicketHandler $createTicketHandler;

    private CreateTicket $createTicket;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $request = new CreateTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            price: 14.42,
            total: 20);

        $this->createTicket = M::mock(CreateTicket::class);
        $this->createTicket->expects('execute')
            ->with(M::on(function ($args) use ($request) {
                return $args == $request;
            }));

        $this->event = new HttpRequestEvent([
            'requestContext' => [
                'http' => [
                    'method' => 'POST',
                ]
            ],
            "pathParameters" => [
                "theaterId" => "THEATER_1"
            ],
            'body' => json_encode([
                'movieId' => 'MOVIE_1',
                'price' => '14.42',
                'total' => '20',
            ]),
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->createTicketHandler = new CreateTicketHandler(createTicket: $this->createTicket);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanCreateTicket(): void
    {
        $response = $this->createTicketHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(201, $response->toApiGatewayFormatV2()['statusCode']);
    }
}