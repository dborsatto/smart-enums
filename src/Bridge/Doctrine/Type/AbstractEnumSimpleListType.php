<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\EnumListConverter\SymbolSeparatedValuesEnumListConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractEnumSimpleListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!isset($column['length'])) {
            return $platform->getClobTypeDeclarationSQL($column);
        }

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    protected function getEnumListConverter(): EnumListConverterInterface
    {
        return new SymbolSeparatedValuesEnumListConverter(',');
    }
}
