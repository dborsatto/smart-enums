<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\Tests\Stub\DoctrineEnumType;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class DoctrineEnumTypeTest extends TestCase
{
    /**
     * @var DoctrineEnumType
     */
    private $type;

    /**
     * @var AbstractPlatform|MockObject
     */
    private $platform;

    protected function setUp(): void
    {
        $this->type = DoctrineEnumType::createForEnum(Enum::class);
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function testConfiguresFieldCorrectly(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));

        $valueMap = [
            [['length' => 50], 'correct-configuration-default'],
            [['length' => 10], 'correct-configuration-10'],
        ];
        $this->platform->method('getVarcharTypeDeclarationSQL')
            ->will($this->returnValueMap($valueMap));

        $column = [];
        $this->assertSame('correct-configuration-default', $this->type->getSQLDeclaration($column, $this->platform));

        $column = ['length' => 10];
        $this->assertSame('correct-configuration-10', $this->type->getSQLDeclaration($column, $this->platform));
    }

    public function testConvertsToPHPValue(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
        $this->assertEquals(
            Enum::fromValue('value1'),
            $this->type->convertToPHPValue('value1', $this->platform)
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

        $type = DoctrineEnumType::createForEnum(stdClass::class);

        $type->convertToPHPValue('value', $this->platform);
    }

    public function testConvertsToDatabaseValue(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
        $this->assertEquals(
            'value1',
            $this->type->convertToDatabaseValue(Enum::fromValue('value1'), $this->platform)
        );
    }

    public function testThrowsAnExceptionConvertingToDatabaseValueIfValueIsNotEnumOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue('value', $this->platform);
    }
}
