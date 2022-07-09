<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\EnumListConverter;

use DBorsatto\SmartEnums\EnumListConverter\SymbolSeparatedValuesEnumListConverter;
use DBorsatto\SmartEnums\Tests\Stub\ConcreteEnum;
use PHPUnit\Framework\TestCase;

class SymbolSeparatedValuesEnumListConverterTest extends TestCase
{
    public function testConvertsToArray(): void
    {
        $converter = new SymbolSeparatedValuesEnumListConverter(',');

        $enums = $converter->convertFromStringToEnumList(ConcreteEnum::class, 'value1,value2');

        $this->assertSame([ConcreteEnum::value1(), ConcreteEnum::value2()], $enums);
    }

    public function testConvertsEmptyStringToArray(): void
    {
        $converter = new SymbolSeparatedValuesEnumListConverter(',');

        $enums = $converter->convertFromStringToEnumList(ConcreteEnum::class, '');

        $this->assertSame([], $enums);
    }

    public function testConvertsToString(): void
    {
        $converter = new SymbolSeparatedValuesEnumListConverter(',');

        $string = $converter->convertFromEnumListToString([
            ConcreteEnum::value1(),
            ConcreteEnum::value2(),
        ]);

        $this->assertSame('value1,value2', $string);
    }
}
