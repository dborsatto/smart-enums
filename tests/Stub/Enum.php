<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Stub;

use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumException;
use function array_keys;
use function array_map;

final class Enum implements EnumInterface
{
    public const UNSUPPORTED_VALUE = 'unsupported_value';
    public const VALUES = ['value1' => 'description1', 'value2' => 'description2'];
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromValue(string $value): self
    {
        if (self::UNSUPPORTED_VALUE === $value) {
            throw SmartEnumException::invalidValue($value, self::class);
        }

        return new self($value);
    }

    public static function fromValues(array $values): array
    {
        return array_map(static function (string $value): self {
            return self::fromValue($value);
        }, $values);
    }

    public static function all(): array
    {
        return self::fromValues(array_keys(self::VALUES));
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDescription(): string
    {
        return self::VALUES[$this->value] ?? $this->value;
    }
}
