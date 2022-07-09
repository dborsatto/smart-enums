<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumListConverterException;
use function is_string;

abstract class AbstractEnumListConverter implements EnumListConverterInterface
{
    public function convertFromStringToEnumList(string $enumClass, string $value): array
    {
        $values = $this->convertToArray($value);
        $enumStrings = [];
        foreach ($values as $arrayValue) {
            if (!is_string($arrayValue)) {
                throw SmartEnumListConverterException::fromInvalidListElement($arrayValue);
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
     * @throws SmartEnumExceptionInterface
     */
    abstract protected function convertToArray(string $value): array;

    /**
     * @param list<string> $values
     */
    abstract protected function convertToString(array $values): string;
}
