<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use function explode;
use function implode;

abstract class AbstractEnumSimpleListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!isset($column['length'])) {
            return $platform->getClobTypeDeclarationSQL($column);
        }

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    protected function convertDatabaseStringToEnumValues(string $value): array
    {
        return explode(',', $value);
    }

    protected function convertEnumValuesToDatabaseString(array $values): string
    {
        return implode(',', $values);
    }
}
