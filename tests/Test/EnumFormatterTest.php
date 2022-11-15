<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test;

use DBorsatto\SmartEnums\EnumFormatter;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use stdClass;
use function array_flip;
use function array_keys;
use function array_values;

class EnumFormatterTest extends TestCase
{
    public function testThrowsAnExceptionIfEnumClassIsNotValid(): void
    {
        $this->expectException(SmartEnumExceptionInterface::class);

        new EnumFormatter(stdClass::class);
    }

    public function testCreatesValueDescriptionArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(Enum::VALUES, $formatter->toValueDescriptionList());
        $this->assertSame(Enum::VALUES, $formatter->toKeyValueList());
    }

    public function testCreatesDescriptionValueArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(array_flip(Enum::VALUES), $formatter->toDescriptionValueList());
        $this->assertSame(array_flip(Enum::VALUES), $formatter->toValueKeyList());
    }

    public function testCreatesValuesArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(array_keys(Enum::VALUES), $formatter->toValues());
    }

    public function testCreatesDescriptionsArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(array_values(Enum::VALUES), $formatter->toDescriptions());
    }
}
