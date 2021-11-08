<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\EnumInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EnumConstraint extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The string {{ value }} is not a valid value of enum {{ enumClass }}';

    /**
     * @var class-string<EnumInterface>
     * @psalm-suppress PropertyNotSetInConstructor
     */
    public $enumClass;

    public function validatedBy(): string
    {
        return EnumValidator::class;
    }

    public function getRequiredOptions(): array
    {
        return ['enumClass'];
    }
}
