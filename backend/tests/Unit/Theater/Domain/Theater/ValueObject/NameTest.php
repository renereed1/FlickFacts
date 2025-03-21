<?php declare(strict_types=1);

namespace FlickFacts\Tests\Unit\Theater\Domain\Theater\ValueObject;

use FlickFacts\Theater\Domain\Theater\ValueObject\Name;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class NameTest extends TestCase
{
    #[Test]
    public function CanCreateName(): void
    {
        $name = new Name(name: 'Name');

        $this->assertEquals('Name', $name);
    }

    #[Test]
    public function ThrowExceptionWhenNameIsLessThen2Character(): void
    {
        $this->expectException(RuntimeException::class);
        new Name(name: 'N');
    }

    #[Test]
    public function ThrowExceptionWhenNameIsMoreThen60Character(): void
    {
        $this->expectException(RuntimeException::class);
        new Name(name: 'N2345678910123456789NN2345678910123456789NN234567891012345678');
    }

    #[Test]
    public function ThrowExceptionWhenInvalidCharacters(): void
    {
        $this->expectException(RuntimeException::class);
        new Name(name: 'Name + Name');
    }

    #[Test]
    public function CanCreateNameWithSpaces(): void
    {
        $name = new Name(name: 'Name Name');
        $this->assertEquals('Name Name', $name->name);
    }
}