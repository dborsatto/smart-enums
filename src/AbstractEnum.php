<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumException;
use function array_keys;
use function array_map;

abstract class AbstractEnum implements EnumInterface
{
    /**
     * @var array<string, static>
     */
    private static array $instances = [];
    protected string $value;

    final private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return static
     */
    protected static function newInstance(string $value): self
    {
        $key = static::class . ':' . $value;
        if (isset(self::$instances[$key])) {
            return self::$instances[$key];
        }

        return self::$instances[$key] = new static($value);
    }

    public static function fromValue(string $value)
    {
        if (!static::isSupportedValue($value)) {
            throw SmartEnumException::invalidValue($value, static::class);
        }

        return static::newInstance($value);
    }

    private static function isSupportedValue(string $value): bool
    {
        foreach (array_keys(static::getValues()) as $key) {
            // Unfortunately, PHP automatically converts array keys that "look" numeric into actual numbers.
            // This means that ['1' => 'value'] will be interpreted as [1 => 'value']
            // For this reason we must force casting to string even though we *shouldn't* need that.
            // The same applies to the "all()" method.
            /** @psalm-suppress RedundantCastGivenDocblockType */
            if ((string) $key === $value) {
                return true;
            }
        }

        return false;
    }

    public static function fromValues(array $values): array
    {
        return array_map(static function (string $value): self {
            return static::fromValue($value);
        }, $values);
    }

    public static function all(): array
    {
        $enums = [];
        foreach (array_keys(static::getValues()) as $value) {
            /** @psalm-suppress RedundantCastGivenDocblockType */
            $enums[] = static::newInstance((string) $value);
        }

        return $enums;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDescription(): string
    {
        return static::getValues()[$this->value];
    }

    /**
     * @return array<string, string>
     */
    abstract protected static function getValues(): array;
}
