<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\Tests\Stub\DoctrineEnumSimpleListType;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class DoctrineEnumSimpleListTypeTest extends TestCase
{
    private DoctrineEnumSimpleListType $type;

    /**
     * @var AbstractPlatform|MockObject
     */
    private $platform;

    protected function setUp(): void
    {
        $this->type = DoctrineEnumSimpleListType::createForEnum(Enum::class);
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testConfiguresFieldCorrectly(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));

        $this->platform->method('getClobTypeDeclarationSQL')
            ->willReturn('correct-configuration-clob');
        $this->platform->method('getVarcharTypeDeclarationSQL')
            ->willReturn('correct-configuration-varchar');

        $column = [];
        $this->assertSame('correct-configuration-clob', $this->type->getSQLDeclaration($column, $this->platform));

        $column = ['length' => 255];
        $this->assertSame('correct-configuration-varchar', $this->type->getSQLDeclaration($column, $this->platform));
    }

    public function testConvertsToPHPValue(): void
    {
        $this->assertSame([], $this->type->convertToPHPValue(null, $this->platform));
        $this->assertSame([], $this->type->convertToPHPValue('', $this->platform));
        $this->assertEquals(
            Enum::fromValues([Enum::VALID_VALUE]),
            $this->type->convertToPHPValue(Enum::VALID_VALUE, $this->platform)
        );
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotStringOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(1, $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfErrorOccursDuringEnumCreation(): void
    {
        $this->expectException(ConversionException::class);

        $type = DoctrineEnumSimpleListType::createForEnum(stdClass::class);

        $type->convertToPHPValue('value', $this->platform);
    }

    public function testConvertsToDatabaseValue(): void
    {
        $this->assertSame('', $this->type->convertToDatabaseValue(null, $this->platform));
        $this->assertSame('', $this->type->convertToDatabaseValue([], $this->platform));
        $this->assertEquals(
            'value1,value2',
            $this->type->convertToDatabaseValue([Enum::fromValue('value1'), Enum::fromValue('value2')], $this->platform)
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
