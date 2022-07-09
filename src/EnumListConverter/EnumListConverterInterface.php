<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;

interface EnumListConverterInterface
{
    /**
     * @param class-string<EnumInterface> $enumClass
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return list<EnumInterface>
     */
    public function convertFromStringToEnumList(string $enumClass, string $value): array;

    /**
     * @param list<EnumInterface> $enums
     *
     * @throws SmartEnumExceptionInterface
     */
    public function convertFromEnumListToString(array $enums): string;
}
