<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\EnumListConverter;

use DBorsatto\SmartEnums\EnumListConverter\SerializedArrayEnumListConverter;
use DBorsatto\SmartEnums\Exception\SmartEnumListCouldNotBeConvertedFromSerializedArrayException;
use DBorsatto\SmartEnums\Tests\Stub\ConcreteEnum;
use PHPUnit\Framework\TestCase;
use function serialize;

class SerializedArrayEnumListConverterTest extends TestCase
{
    public function testConvertsToArray(): void
    {
        $converter = new SerializedArrayEnumListConverter();

        $enums = $converter->convertFromStringToEnumList(ConcreteEnum::class, serialize(['value1', 'value2']));

        $this->assertSame([ConcreteEnum::value1(), ConcreteEnum::value2()], $enums);
    }

    public function testThrowsDuringConversionToArrayIfSerializedStringIsNotValid(): void
    {
        $value = '{]';
        $this->expectExceptionObject(SmartEnumListCouldNotBeConvertedFromSerializedArrayException::create($value));

        $converter = new SerializedArrayEnumListConverter();

        $converter->convertFromStringToEnumList(ConcreteEnum::class, $value);
    }

    public function testThrowsDuringConversionToArrayIfSerializedStringIsNotAnArray(): void
    {
        $value = serialize(1);
        $this->expectExceptionObject(SmartEnumListCouldNotBeConvertedFromSerializedArrayException::create($value));

        $converter = new SerializedArrayEnumListConverter();

        $converter->convertFromStringToEnumList(ConcreteEnum::class, $value);
    }

    public function testConvertsToString(): void
    {
        $converter = new SerializedArrayEnumListConverter();

        $string = $converter->convertFromEnumListToString([
            ConcreteEnum::value1(),
            ConcreteEnum::value2(),
        ]);

        $this->assertSame(serialize(['value1', 'value2']), $string);
    }
}
