<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use Exception;
use Throwable;
use function gettype;
use function sprintf;

class SmartEnumListConverterException extends Exception implements SmartEnumExceptionInterface
{
    final public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromJson(string $value, string $propertyKey): self
    {
        return new self(sprintf(
            'Enum list could not be created from JSON "%s" at property with key "%s".',
            $value,
            $propertyKey,
        ));
    }

    public static function fromSerializedArray(string $value): self
    {
        return new self(sprintf(
            'Enum list could not be created from serialized array "%s"',
            $value,
        ));
    }

    /**
     * @param mixed $value
     */
    public static function fromInvalidListElement($value): self
    {
        return new self(sprintf(
            'Enum list could not be created because it contains an element of type "%s".',
            gettype($value),
        ));
    }
}
