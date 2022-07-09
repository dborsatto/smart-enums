<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\EnumListConverter\JsonEnumListConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractEnumJsonListType extends AbstractEnumGenericArrayType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    protected function getEnumListConverter(): EnumListConverterInterface
    {
        return new JsonEnumListConverter('values');
    }
}
