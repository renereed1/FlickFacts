<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Infrastructure\Service;

use FlickFacts\Theater\Infrastructure\Service\DefaultDiscountService;
use FlickFacts\Theater\Sales\Domain\Sales\ValueObject\Discount;
use Mockery as M;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DefaultDiscountServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        M::close();
    }

    #[Test]
    public function NoDiscount(): void
    {
        $service = new DefaultDiscountService();

        $discount = $service->getDiscount('');

        $this->assertEquals(new Discount(), $discount);
    }

    #[Test]
    public function Discount10Percent(): void
    {
        $service = new DefaultDiscountService();

        $discount = $service->getDiscount('everyone-10');

        $this->assertEquals(new Discount(10), $discount);
    }
}