<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use function gettype;
use function sprintf;

class SmartEnumListCouldNotBeConvertedFromInvalidListElementException extends AbstractSmartEnumException implements SmartEnumListConverterExceptionInterface
{
    /**
     * @param mixed $value
     */
    public static function create($value): self
    {
        return new self(sprintf(
            'Smart enum list could not be converted because it contains an element of type "%s".',
            gettype($value),
        ));
    }
}
