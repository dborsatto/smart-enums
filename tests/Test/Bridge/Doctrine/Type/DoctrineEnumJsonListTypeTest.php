<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\Tests\Stub\DoctrineEnumJsonListType;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class DoctrineEnumJsonListTypeTest extends TestCase
{
    private DoctrineEnumJsonListType $type;

    /**
     * @var AbstractPlatform|MockObject
     */
    private $platform;

    protected function setUp(): void
    {
        $this->type = DoctrineEnumJsonListType::createForEnum(Enum::class);
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testConfiguresFieldCorrectly(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));

        $this->platform->method('getJsonTypeDeclarationSQL')
            ->willReturn('correct-configuration');

        $column = [];
        $this->assertSame('correct-configuration', $this->type->getSQLDeclaration($column, $this->platform));
    }

    public function testConvertsToPHPValue(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
        $this->assertSame([], $this->type->convertToPHPValue('', $this->platform));
        $this->assertEquals(
            Enum::fromValues([Enum::VALID_VALUE]),
            $this->type->convertToPHPValue('{"values":["value1"]}', $this->platform),
        );
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotStringOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(1, $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfStringCannotBeDecoded(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue('{]', $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfDecodedValueIsNotArray(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue('{}', $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfDecodedArrayIsNotOfStrings(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue('{"values":[1, 2]}', $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfErrorOccursDuringEnumCreation(): void
    {
        $this->expectException(ConversionException::class);

        $type = DoctrineEnumJsonListType::createForEnum(stdClass::class);

        $type->convertToPHPValue('value', $this->platform);
    }

    public function testConvertsToDatabaseValue(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
        $this->assertJsonStringEqualsJsonString('{"values":[]}', $this->type->convertToDatabaseValue([], $this->platform));
        $this->assertJsonStringEqualsJsonString(
            '{"values":["value1","value2"]}',
            $this->type->convertToDatabaseValue([Enum::fromValue('value1'), Enum::fromValue('value2')], $this->platform),
        );
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotArrayOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue('', $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotArrayOfEnums(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue([''], $this->platform);
    }
}
