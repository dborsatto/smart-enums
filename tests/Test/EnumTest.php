<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test;

use DBorsatto\SmartEnums\Exception\SmartEnumException;
use DBorsatto\SmartEnums\Tests\Stub\ConcreteEnum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testIdentityIsRetained(): void
    {
        $this->assertSame(ConcreteEnum::fromValue('value1'), ConcreteEnum::value1());
        $this->assertSame(ConcreteEnum::fromValue('value2'), ConcreteEnum::value2());
        $this->assertNotSame(ConcreteEnum::value1(), ConcreteEnum::value2());
    }

    public function testCreatesFromValue(): void
    {
        $enum = ConcreteEnum::fromValue('value1');

        $this->assertSame('value1', $enum->getValue());
        $this->assertSame('description1', $enum->getDescription());
        $this->assertSame('value1', $enum->__toString());
    }

    public function testThrowsAnExceptionIfValueIsNotValid(): void
    {
        $this->expectException(SmartEnumException::class);

        ConcreteEnum::fromValue('invalid');
    }

    public function testsCreatesFromValues(): void
    {
        $enums = ConcreteEnum::fromValues(['value1', 'value2']);

        $this->assertSame([ConcreteEnum::value1(), ConcreteEnum::value2()], $enums);
    }

    public function testThrowsAnExceptionIfValuesAreNotValid(): void
    {
        $this->expectException(SmartEnumException::class);

        ConcreteEnum::fromValues(['invalid']);
    }

    public function testCreatesAllValues(): void
    {
        $enums = ConcreteEnum::all();

        $this->assertSame([ConcreteEnum::value1(), ConcreteEnum::value2()], $enums);
    }
}
