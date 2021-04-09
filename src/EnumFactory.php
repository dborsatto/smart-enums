<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumException;
use function is_subclass_of;

class EnumFactory
{
    /**
     * @var string
     * @psalm-var class-string<EnumInterface> $enumClass
     */
    private $enumClass;

    /**
     * @param string $enumClass
     * @psalm-param class-string<EnumInterface> $enumClass
     *
     * @throws SmartEnumException
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
     * @throws SmartEnumException
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
     * @throws SmartEnumException
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
