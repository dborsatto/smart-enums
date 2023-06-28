<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumListConverterExceptionInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumListCouldNotBeConvertedFromInvalidListElementException;
use function is_string;

abstract class AbstractEnumListConverter implements EnumListConverterInterface
{
    public function convertFromStringToEnumList(string $enumClass, string $value): array
    {
        $values = $this->convertToArray($value);
        $enumStrings = [];
        foreach ($values as $arrayValue) {
            if (!is_string($arrayValue)) {
                throw SmartEnumListCouldNotBeConvertedFromInvalidListElementException::create($arrayValue);
            }

            $enumStrings[] = $arrayValue;
        }

        $factory = new EnumFactory($enumClass);

        return $factory->fromValues($enumStrings);
    }

    public function convertFromEnumListToString(array $enums): string
    {
        $values = [];
        foreach ($enums as $enum) {
            $values[] = $enum->getValue();
        }

        return $this->convertToString($values);
    }

    /**
     * @throws SmartEnumListConverterExceptionInterface
     */
    abstract protected function convertToArray(string $value): array;

    /**
     * @param list<string> $values
     */
    abstract protected function convertToString(array $values): string;
}
