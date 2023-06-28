<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\Tests\Stub\DoctrineEnumType;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DoctrineEnumTypeTest extends TestCase
{
    private DoctrineEnumType $type;

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
            ->willReturnMap($valueMap);

        $column = [];
        $this->assertSame('correct-configuration-default', $this->type->getSQLDeclaration($column, $this->platform));

        $column = ['length' => 10];
        $this->assertSame('correct-configuration-10', $this->type->getSQLDeclaration($column, $this->platform));
    }

    public function testConvertsToPHPValue(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
        $this->assertEquals(
            Enum::fromValue(Enum::VALID_VALUE),
            $this->type->convertToPHPValue(Enum::VALID_VALUE, $this->platform),
        );
    }

    public function testThrowsAnExceptionConvertingToPHPValueIfValueIsNotStringOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(1, $this->platform);
    }

    public function testConvertsToDatabaseValue(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
        $this->assertEquals(
            Enum::VALID_VALUE,
            $this->type->convertToDatabaseValue(Enum::fromValue(Enum::VALID_VALUE), $this->platform),
        );
    }

    public function testThrowsAnExceptionConvertingToDatabaseValueIfValueIsNotEnumOrNull(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue('value', $this->platform);
    }
}
