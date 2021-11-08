<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_string;

class EnumValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EnumConstraint) {
            throw new UnexpectedTypeException($constraint, EnumConstraint::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            $factory = new EnumFactory($constraint->enumClass);
        } catch (SmartEnumException $exception) {
            throw new ConstraintDefinitionException('Enum class property of constraint is not valid');
        }

        try {
            $factory->fromValue($value);
        } catch (SmartEnumException $exception) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ enumClass }}', $constraint->enumClass)
                ->addViolation();
        }
    }
}
