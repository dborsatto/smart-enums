<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\Bridge\Symfony\Validator\EnumListConstraint;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use function sprintf;

class EnumListContraintValidatorTest extends TestCase
{
    public function testErrorsAreDetected(): void
    {
        $validator = (new ValidatorBuilder())
            ->getValidator();

        $violations = $validator->validate([Enum::VALID_VALUE], [
            new EnumListConstraint(['enumClass' => Enum::class]),
        ]);
        $this->assertCount(0, $violations);

        $violations = $validator->validate([Enum::UNSUPPORTED_VALUE], [
            new EnumListConstraint(['enumClass' => Enum::class, 'message' => 'This value is not valid']),
        ]);
        $this->assertCount(1, $violations);
        /** @var ConstraintViolationInterface $violation */
        $violation = $violations[0];
        $this->assertSame('This value is not valid', $violation->getMessage());

        $violations = $validator->validate([Enum::UNSUPPORTED_VALUE], [
            new EnumListConstraint(['enumClass' => Enum::class]),
        ]);
        $this->assertCount(1, $violations);
        /** @var ConstraintViolationInterface $violation */
        $violation = $violations[0];
        $this->assertSame(sprintf(
            'The strings "%s" are not valid values of enum "%s"',
            Enum::UNSUPPORTED_VALUE,
            Enum::class
        ), $violation->getMessage());

        $violations = $validator->validate([1], [
            new EnumListConstraint(['enumClass' => Enum::class]),
        ]);
        $this->assertCount(1, $violations);
    }
}
