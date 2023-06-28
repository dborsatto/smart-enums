<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use DBorsatto\SmartEnums\Exception\SmartEnumListCouldNotBeConvertedFromSerializedArrayException;
use Exception;
use function is_array;
use function restore_error_handler;
use function serialize;
use function set_error_handler;
use function unserialize;

class SerializedArrayEnumListConverter extends AbstractEnumListConverter
{
    protected function convertToArray(string $value): array
    {
        set_error_handler(static function () use ($value): void {
            throw SmartEnumListCouldNotBeConvertedFromSerializedArrayException::create($value);
        });

        try {
            $values = unserialize($value);
        } catch (Exception $exception) {
            throw SmartEnumListCouldNotBeConvertedFromSerializedArrayException::create($value);
        } finally {
            restore_error_handler();
        }

        if (!is_array($values)) {
            throw SmartEnumListCouldNotBeConvertedFromSerializedArrayException::create($value);
        }

        return $values;
    }

    protected function convertToString(array $values): string
    {
        return serialize($values);
    }
}
