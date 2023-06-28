<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use function sprintf;

class SmartEnumListCouldNotBeConvertedFromJsonException extends AbstractSmartEnumException implements SmartEnumListConverterExceptionInterface
{
    public static function create(string $value, string $propertyKey): self
    {
        return new self(sprintf(
            'Smart enum list could not be converted from JSON "%s" at property with key "%s".',
            $value,
            $propertyKey,
        ));
    }
}
