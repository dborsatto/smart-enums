<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\EnumListConverter\SerializedArrayEnumListConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractEnumListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    protected function getEnumListConverter(): EnumListConverterInterface
    {
        return new SerializedArrayEnumListConverter();
    }
}
