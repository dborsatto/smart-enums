<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumException;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use function is_subclass_of;

class EnumFactory
{
    /**
     * @var class-string<EnumInterface>
     */
    private string $enumClass;

    /**
     * @param class-string<EnumInterface> $enumClass
     *
     * @throws SmartEnumExceptionInterface
     */
    public function __construct(string $enumClass)
    {
        if (!is_subclass_of($enumClass, EnumInterface::class)) {
            throw SmartEnumException::invalidEnumClass($enumClass);
        }

        $this->enumClass = $enumClass;
    }

    /**
     * @param string $value
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return EnumInterface
     */
    public function fromValue(string $value): EnumInterface
    {
        return ($this->enumClass)::fromValue($value);
    }

    /**
     * @param list<string> $values
     *
     * @throws SmartEnumExceptionInterface
     *
     * @return list<EnumInterface>
     */
    public function fromValues(array $values): array
    {
        return ($this->enumClass)::fromValues($values);
    }

    /**
     * @return list<EnumInterface>
     */
    public function all(): array
    {
        return ($this->enumClass)::all();
    }
}
