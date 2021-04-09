<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\Tests\Stub\DoctrineEnumListType;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use function serialize;

class DoctrineEnumListTypeTest extends TestCase
{
    /**
     * @var DoctrineEnumListType
     */
    private $type;

    /**
     * @var AbstractPlatform|MockObject
     */
    private $platform;

    protected function setUp(): void
    {
        $this->type = DoctrineEnumListType::createForEnum(Enum::class);
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testConfiguresFieldCorrectly(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));

        $this->platform->method('getClobTypeDeclarationSQL')
            ->willReturn('correct-configuration');

        $column = [];
        $this->assertSame('correct-configuration', $this->type->getSQLDeclaration($column, $this->platform));
    }

    public function testConvertsToPHPValue(): void
    {
        $this->assertSame([], $this->type->convertToPHPValue(null, $this->platform));
        $this->assertSame([], $this->type->convertToPHPValue('', $this->platform));
        $this->assertEquals(
            Enum::fromValues(['value1']),
            $this->type->convertToPHPValue(serialize(['value1']), $this->platform)
        );
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotStringOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(1, $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfStringCannotBeUnserialized(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue('{]', $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfUnserializedValueIsNotArray(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(serialize('1'), $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfUnserializedArrayIsNotOfStrings(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(serialize([100]), $this->platform);
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfErrorOccursDuringEnumCreation(): void
    {
        $this->expectException(ConversionException::class);

        $type = DoctrineEnumListType::createForEnum(stdClass::class);

        $type->convertToPHPValue('value', $this->platform);
    }

    public function testConvertsToDatabaseValue(): void
    {
        $this->assertSame(serialize([]), $this->type->convertToDatabaseValue(null, $this->platform));
        $this->assertSame(serialize([]), $this->type->convertToDatabaseValue([], $this->platform));
        $this->assertEquals(
            serialize(['value1', 'value2']),
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
