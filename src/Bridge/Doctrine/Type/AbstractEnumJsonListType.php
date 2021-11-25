<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Throwable;
use function array_values;
use function is_array;
use function json_decode;
use function json_encode;
use function json_last_error_msg;
use const JSON_THROW_ON_ERROR;

abstract class AbstractEnumJsonListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    protected function convertDatabaseStringToEnumValues(string $value): array
    {
        try {
            $decoded = json_decode($value, true, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw ConversionException::conversionFailedUnserialization($value, $exception->getMessage());
        }

        if (!is_array($decoded)) {
            throw ConversionException::conversionFailedUnserialization($value, 'Invalid JSON');
        }

        if (!isset($decoded['values'])) {
            throw ConversionException::conversionFailedUnserialization($value, 'Invalid JSON');
        }

        if (!is_array($decoded['values'])) {
            throw ConversionException::conversionFailedUnserialization($value, 'Invalid JSON');
        }

        return array_values($decoded['values']);
    }

    protected function convertEnumValuesToDatabaseString(array $values): string
    {
        $encoded = json_encode(['values' => $values]);
        if ($encoded === false) {
            throw ConversionException::conversionFailedSerialization(
                $values,
                'string',
                json_last_error_msg()
            );
        }

        return $encoded;
    }
}
