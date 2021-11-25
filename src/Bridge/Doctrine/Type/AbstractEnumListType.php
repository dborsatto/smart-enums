<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Exception;
use function array_values;
use function is_array;
use function restore_error_handler;
use function serialize;
use function set_error_handler;
use function unserialize;

abstract class AbstractEnumListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    protected function convertDatabaseStringToEnumValues(string $value): array
    {
        /** @psalm-suppress UnusedClosureParam */
        set_error_handler(function (int $code, string $message): bool {
            throw ConversionException::conversionFailedUnserialization($this->getName(), $message);
        });

        try {
            $values = unserialize($value);
        } catch (Exception $exception) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        } finally {
            restore_error_handler();
        }

        if (!is_array($values)) {
            throw ConversionException::conversionFailedUnserialization(
                $this->getName(),
                'Invalid unserialized value'
            );
        }

        return array_values($values);
    }

    protected function convertEnumValuesToDatabaseString(array $values): string
    {
        return serialize($values);
    }
}
