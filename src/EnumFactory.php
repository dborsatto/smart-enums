<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;

/**
 * @template T of EnumInterface
 */
class EnumFactory
{
    /**
     * @var class-string<T>
     */
    private string $enumClass;

    /**
     * @param class-string<T> $enumClass
     */
    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * @param non-empty-string $value
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return T
     */
    public function fromValue(string $value): EnumInterface
    {
        return ($this->enumClass)::fromValue($value);
    }

    /**
     * @param list<non-empty-string> $values
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return list<T>
     */
    public function fromValues(array $values): array
    {
        return ($this->enumClass)::fromValues($values);
    }

    /**
     * @return non-empty-list<T>
     */
    public function all(): array
    {
        return ($this->enumClass)::all();
    }
}
