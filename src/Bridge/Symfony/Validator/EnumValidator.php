<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_string;

class EnumValidator extends ConstraintValidator
{
    /**
     * @psalm-suppress MissingParamType
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EnumConstraint) {
            throw new UnexpectedTypeException($constraint, EnumConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $factory = new EnumFactory($constraint->enumClass);

        try {
            $factory->fromValue($value);
        } catch (SmartEnumExceptionInterface $exception) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ enumClass }}', $constraint->enumClass)
                ->addViolation();
        }
    }
}
