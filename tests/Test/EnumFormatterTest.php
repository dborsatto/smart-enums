<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test;

use DBorsatto\SmartEnums\EnumFormatter;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use stdClass;
use function array_flip;

class EnumFormatterTest extends TestCase
{
    public function testThrowsAnExceptionIfEnumClassIsNotValid(): void
    {
        $this->expectException(SmartEnumExceptionInterface::class);

        new EnumFormatter(stdClass::class);
    }

    public function testCreatesKeyValueArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(Enum::VALUES, $formatter->toKeyValueList());
    }

    public function testCreatesValueKeyArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(array_flip(Enum::VALUES), $formatter->toValueKeyList());
    }
}
