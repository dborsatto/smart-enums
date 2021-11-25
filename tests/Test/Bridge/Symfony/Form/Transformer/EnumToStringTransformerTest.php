<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Symfony\Form\Transformer;

use DBorsatto\SmartEnums\Bridge\Symfony\Form\Transformer\EnumToStringTransformer;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EnumToStringTransformerTest extends TestCase
{
    private EnumToStringTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new EnumToStringTransformer(Enum::class);
    }

    public function testTransforms(): void
    {
        $this->assertNull($this->transformer->transform(null));
        $this->assertSame(Enum::VALID_VALUE, $this->transformer->transform(Enum::fromValue(Enum::VALID_VALUE)));
        $this->assertSame([Enum::VALID_VALUE], $this->transformer->transform([Enum::fromValue(Enum::VALID_VALUE)]));
    }

    public function testThrowsAnExceptionDuringTransformIfValueIsNotAnArrayOfEnums(): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->transform(['1']);
    }

    public function testThrowsAnExceptionDuringTransformIfValueIsNotAnArrayOrEnumOrNull(): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->transform('1');
    }

    public function testReverseTransforms(): void
    {
        $this->assertNull($this->transformer->reverseTransform(null));
        $this->assertEquals(Enum::fromValue(Enum::VALID_VALUE), $this->transformer->reverseTransform(Enum::VALID_VALUE));
        $this->assertEquals([Enum::fromValue(Enum::VALID_VALUE)], $this->transformer->reverseTransform([Enum::VALID_VALUE]));
    }

    public function testThrowsAnExceptionDuringReverseTransformingConvertingToPHPValueIfErrorOccursDuringEnumCreation(): void
    {
        $this->expectException(TransformationFailedException::class);

        $transformer = new EnumToStringTransformer(stdClass::class);

        $transformer->reverseTransform(Enum::VALID_VALUE);
    }

    public function testThrowsAnExceptionDuringReverseTransformIfValueIsNotAnArrayOfStrings(): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->reverseTransform([1]);
    }

    public function testThrowsAnExceptionDuringReverseTransformIfValueIsNotAnArrayOrStringOrNull(): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->reverseTransform(1);
    }
}
