<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Test\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\Bridge\Symfony\Validator\EnumConstraint;
use DBorsatto\SmartEnums\Tests\Stub\Enum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use function sprintf;

class EnumConstraintValidatorTest extends TestCase
{
    public function testErrorsAreDetected(): void
    {
        $validator = (new ValidatorBuilder())
            ->getValidator();

        $violations = $validator->validate(Enum::VALID_VALUE, [
            new EnumConstraint(['enumClass' => Enum::class]),
        ]);
        $this->assertCount(0, $violations);

        $violations = $validator->validate(Enum::UNSUPPORTED_VALUE, [
            new EnumConstraint(['enumClass' => Enum::class, 'message' => 'This value is not valid']),
        ]);
        $this->assertCount(1, $violations);
        /** @var ConstraintViolationInterface $violation */
        $violation = $violations[0];
        $this->assertSame('This value is not valid', $violation->getMessage());

        $violations = $validator->validate(Enum::UNSUPPORTED_VALUE, [
            new EnumConstraint(['enumClass' => Enum::class]),
        ]);
        $this->assertCount(1, $violations);
        /** @var ConstraintViolationInterface $violation */
        $violation = $violations[0];
        $this->assertSame(sprintf(
            'The string %s is not a valid value of enum %s',
            Enum::UNSUPPORTED_VALUE,
            Enum::class
        ), $violation->getMessage());
    }
}
