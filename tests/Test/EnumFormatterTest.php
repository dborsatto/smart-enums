<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test;

use DBorsatto\SmartEnums\EnumFormatter;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use function array_flip;
use function array_keys;
use function array_values;

class EnumFormatterTest extends TestCase
{
    public function testCreatesValueDescriptionArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(Enum::VALUES, $formatter->toValueDescriptionList());
    }

    public function testCreatesDescriptionValueArrayFromEnumClass(): void
    {
        $formatter = new EnumFormatter(Enum::class);

        $this->assertSame(array_flip(Enum::VALUES), $formatter->toDescriptionValueList());
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
