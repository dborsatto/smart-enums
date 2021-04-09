<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumException;

interface EnumInterface
{
    /**
     * @param string $value
     *
     * @throws SmartEnumException
     *
     * @return static
     */
    public static function fromValue(string $value);

    /**
     * @param list<string> $values
     *
     * @throws SmartEnumException
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
