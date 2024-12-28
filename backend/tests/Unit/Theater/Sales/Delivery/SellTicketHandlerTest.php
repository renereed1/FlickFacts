<?php

namespace FlickFacts\Tests\Unit\Theater\Sales\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Sales\Delivery\SellTicketHandler;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicket;
use FlickFacts\Theater\Sales\Interactor\SellTicket\SellTicketRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SellTicketHandlerTest extends TestCase
{
    private SellTicketHandler $sellTicketHandler;

    private SellTicket $sellTicket;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $request = new SellTicketRequest(theaterId: 'THEATER_1',
            movieId: 'MOVIE_1',
            quantity: 1);

        $this->sellTicket = m::mock(SellTicket::class);
        $this->sellTicket->expects('execute')
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
                'quantity' => 1,
            ]),
        ]);

        $this->context = new Context('1234',
            1234,
            '4321',
            '2341');

        $this->sellTicketHandler = new SellTicketHandler(sellTicket: $this->sellTicket);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanSellTicket(): void
    {
        $response = $this->sellTicketHandler->handleRequest(event: $this->event,
            context: $this->context);

        $this->assertEquals(200, $response->toApiGatewayFormatV2()['statusCode']);
    }
}