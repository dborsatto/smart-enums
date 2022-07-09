<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;

interface EnumInterface
{
    /**
     * @throws SmartEnumExceptionInterface
     *
     * @return static
     */
    public static function fromValue(string $value);

    /**
     * @param list<string> $values
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return list<static>
     */
    public static function fromValues(array $values): array;

    /**
     * @return list<static>
     */
    public static function all(): array;

    public function getValue(): string;

    public function getDescription(): string;
}
