<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;

class EnumFactoryTest extends TestCase
{
    public function testForwardsCallsToEnumClass(): void
    {
        $factory = new EnumFactory(Enum::class);

        $this->assertEquals($factory->fromValue(Enum::VALID_VALUE), Enum::fromValue(Enum::VALID_VALUE));
        $this->assertEquals($factory->fromValues([Enum::VALID_VALUE]), Enum::fromValues([Enum::VALID_VALUE]));
        $this->assertEquals($factory->all(), Enum::all());
    }

    public function testThrowsAnExceptionWhenCreatingEnumFromUnsupportedValue(): void
    {
        $this->expectException(SmartEnumExceptionInterface::class);

        $factory = new EnumFactory(Enum::class);
        $factory->fromValue(Enum::UNSUPPORTED_VALUE);
    }

    public function testThrowsAnExceptionWhenCreatingEnumFromUnsupportedValues(): void
    {
        $this->expectException(SmartEnumExceptionInterface::class);

        $factory = new EnumFactory(Enum::class);
        $factory->fromValues([Enum::UNSUPPORTED_VALUE]);
    }
}
