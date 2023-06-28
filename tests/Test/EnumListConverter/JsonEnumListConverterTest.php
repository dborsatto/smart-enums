<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\EnumListConverter;

use DBorsatto\SmartEnums\EnumListConverter\JsonEnumListConverter;
use DBorsatto\SmartEnums\Exception\SmartEnumListCouldNotBeConvertedFromJsonException;
use DBorsatto\SmartEnums\Tests\Stub\ConcreteEnum;
use PHPUnit\Framework\TestCase;

class JsonEnumListConverterTest extends TestCase
{
    public function testConvertsToArray(): void
    {
        $json = '{"values":["value1","value2"]}';
        $converter = new JsonEnumListConverter('values');

        $enums = $converter->convertFromStringToEnumList(ConcreteEnum::class, $json);

        $this->assertSame([ConcreteEnum::value1(), ConcreteEnum::value2()], $enums);
    }

    public function testThrowsDuringConversionToArrayIfJsonIsNotValid(): void
    {
        $json = '{]';
        $this->expectExceptionObject(SmartEnumListCouldNotBeConvertedFromJsonException::create($json, 'values'));

        $converter = new JsonEnumListConverter('values');

        $converter->convertFromStringToEnumList(ConcreteEnum::class, $json);
    }

    public function testThrowsDuringConversionToArrayIfConversionResultIsNotArray(): void
    {
        $json = 'null';
        $this->expectExceptionObject(SmartEnumListCouldNotBeConvertedFromJsonException::create($json, 'values'));

        $converter = new JsonEnumListConverter('values');

        $converter->convertFromStringToEnumList(ConcreteEnum::class, $json);
    }

    public function testThrowsDuringConversionToArrayIfDecodedArrayDoesNotContainAnArrayAtKey(): void
    {
        $json = '{"values":null}';
        $this->expectExceptionObject(SmartEnumListCouldNotBeConvertedFromJsonException::create($json, 'values'));

        $converter = new JsonEnumListConverter('values');

        $converter->convertFromStringToEnumList(ConcreteEnum::class, $json);
    }

    public function testConvertsToString(): void
    {
        $converter = new JsonEnumListConverter('values');

        $string = $converter->convertFromEnumListToString([
            ConcreteEnum::value1(),
            ConcreteEnum::value2(),
        ]);

        $this->assertJsonStringEqualsJsonString('{"values":["value1","value2"]}', $string);
    }
}
