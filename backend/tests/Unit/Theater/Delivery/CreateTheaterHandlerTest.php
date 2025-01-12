<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Delivery;

use Bref\Context\Context;
use Bref\Event\Http\HttpRequestEvent;
use FlickFacts\Theater\Delivery\CreateTheaterHandler;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheater;
use FlickFacts\Theater\Interactor\CreateTheater\CreateTheaterRequest;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateTheaterHandlerTest extends TestCase
{
    private CreateTheaterHandler $createTheaterHandler;

    private CreateTheater $createTheater;

    private HttpRequestEvent $event;

    private Context $context;

    public function setUp(): void
    {
        parent::setUp();

        $request = new CreateTheaterRequest(name: 'Theater 1');

        $this->createTheater = M::mock(CreateTheater::class);
        $this->createTheater->expects('execute')
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
                'name' => 'Theater 1',
            ]),
        ]);

        $this->context = new Context('1234',
            1,
            '4321',
            '3214');

        $this->createTheaterHandler = new CreateTheaterHandler(createTheater: $this->createTheater);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function CanCreateTheater(): void
    {
        $response = $this->createTheaterHandler
            ->handleRequest(event: $this->event,
                context: $this->context);

        $this->assertEquals(201, $response->toApiGatewayFormatV2()['statusCode']);
    }
}