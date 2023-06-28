<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use DBorsatto\SmartEnums\Exception\SmartEnumListCouldNotBeConvertedFromJsonException;
use Throwable;
use function is_array;
use function json_decode;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class JsonEnumListConverter extends AbstractEnumListConverter
{
    private string $propertyName;

    public function __construct(string $propertyName)
    {
        $this->propertyName = $propertyName;
    }

    protected function convertToArray(string $value): array
    {
        try {
            $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw SmartEnumListCouldNotBeConvertedFromJsonException::create($value, $this->propertyName);
        }

        if (!is_array($decoded)) {
            throw SmartEnumListCouldNotBeConvertedFromJsonException::create($value, $this->propertyName);
        }

        $values = $decoded[$this->propertyName] ?? null;
        if (!is_array($values)) {
            throw SmartEnumListCouldNotBeConvertedFromJsonException::create($value, $this->propertyName);
        }

        return $values;
    }

    protected function convertToString(array $values): string
    {
        return json_encode([$this->propertyName => $values]);
    }
}
