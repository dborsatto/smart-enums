<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Validator;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function array_values;
use function implode;
use function is_array;
use function is_string;

class EnumListValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EnumListConstraint) {
            throw new UnexpectedTypeException($constraint, EnumListConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'string[]');
        }

        foreach ($value as $item) {
            if (!is_string($item)) {
                throw new UnexpectedValueException($value, 'string[]');
            }
        }

        /** @var list<string> $value */
        $value = array_values($value);

        try {
            $factory = new EnumFactory($constraint->enumClass);
        } catch (SmartEnumExceptionInterface $exception) {
            throw new ConstraintDefinitionException('Enum class property of constraint is not valid');
        }

        try {
            $factory->fromValues($value);
        } catch (SmartEnumExceptionInterface $exception) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', implode(', ', $value))
                ->setParameter('{{ enumClass }}', $constraint->enumClass)
                ->addViolation();
        }
    }
}
