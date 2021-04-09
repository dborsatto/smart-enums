<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Form\Transformer;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use function is_array;
use function is_string;

class EnumToStringTransformer implements DataTransformerInterface
{
    /**
     * @var string
     * @psalm-var class-string<EnumInterface>
     */
    private $enumClass;

    /**
     * @param string $enumClass
     * @psalm-param class-string<EnumInterface> $enumClass
     */
    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * @param list<EnumInterface>|EnumInterface|mixed $value
     *
     * @throws TransformationFailedException
     *
     * @return list<string>|string|null
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

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

        throw new TransformationFailedException();
    }

    /**
     * @param list<string>|string|mixed $value
     *
     * @throws TransformationFailedException
     *
     * @return list<EnumInterface>|EnumInterface|null
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        try {
            $factory = new EnumFactory($this->enumClass);

            if (is_string($value)) {
                return $factory->fromValue($value);
            }

            if (is_array($value)) {
                $enums = [];
                foreach ($value as $enumValue) {
                    if (!is_string($enumValue)) {
                        throw new TransformationFailedException();
                    }

                    $enums[] = $factory->fromValue($enumValue);
                }

                return $enums;
            }
        } catch (SmartEnumException $exception) {
            throw new TransformationFailedException();
        }

        throw new TransformationFailedException();
    }
}
