<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;

interface EnumInterface
{
    /**
     * @param non-empty-string $value
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return static
     */
    public static function fromValue(string $value);

    /**
     * @param list<non-empty-string> $values
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return list<static>
     */
    public static function fromValues(array $values): array;

    /**
     * @return non-empty-list<static>
     */
    public static function all(): array;

    /**
     * @return non-empty-string
     */
    public function getValue(): string;

    /**
     * @return non-empty-string
     */
    public function getDescription(): string;
}
