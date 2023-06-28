<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use function sprintf;

class SmartEnumListCouldNotBeConvertedFromSerializedArrayException extends AbstractSmartEnumException implements SmartEnumListConverterExceptionInterface
{
    public static function create(string $value): self
    {
        return new self(sprintf(
            'Smart enum list could not be converted from serialized array "%s"',
            $value,
        ));
    }
}
