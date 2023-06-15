<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Form\Transformer;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use function is_array;
use function is_string;

/**
 * @implements DataTransformerInterface<list<EnumInterface>|EnumInterface, list<string>|string>
 */
class EnumToStringTransformer implements DataTransformerInterface
{
    /**
     * @var class-string<EnumInterface>
     */
    private string $enumClass;

    /**
     * @param class-string<EnumInterface> $enumClass
     */
    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * @throws TransformationFailedException
     */
    public function transform($value)
    {
        if ($value instanceof EnumInterface) {
            return $value->getValue();
        }

        if (is_array($value)) {
            $values = [];
            foreach ($value as $enum) {
                if (!$enum instanceof EnumInterface) {
                    throw new TransformationFailedException();
                }

                $values[] = $enum->getValue();
            }

            return $values;
        }

        return null;
    }

    /**
     * @throws TransformationFailedException
     */
    public function reverseTransform($value)
    {
        try {
            $factory = new EnumFactory($this->enumClass);

            if (is_string($value)) {
                return $factory->fromValue($value);
            }

            if (is_array($value)) {
                $enums = [];
                foreach ($value as $enumValue) {
                    $enums[] = $factory->fromValue($enumValue);
                }

                return $enums;
            }
        } catch (SmartEnumExceptionInterface $exception) {
            throw new TransformationFailedException();
        }

        return null;
    }
}
