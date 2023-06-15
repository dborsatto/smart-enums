<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\EnumInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @psalm-suppress MissingConstructor
 */
class EnumListConstraint extends Constraint
{
    public string $message = 'The strings "{{ value }}" are not valid values of enum "{{ enumClass }}"';

    /**
     * @var class-string<EnumInterface>
     */
    public $enumClass;

    public function validatedBy(): string
    {
        return EnumListValidator::class;
    }

    public function getRequiredOptions(): array
    {
        return ['enumClass'];
    }
}
