<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use DBorsatto\SmartEnums\EnumInterface;
use function sprintf;

class SmartEnumCouldNotBeCreatedFromInvalidValueException extends AbstractSmartEnumException
{
    /**
     * @param non-empty-string            $value
     * @param class-string<EnumInterface> $enumClass
     */
    public static function create(string $value, string $enumClass): self
    {
        return new self(sprintf(
            'Smart enum of class "%s" could not be created from invalid value "%s".',
            $enumClass,
            $value,
        ));
    }
}
