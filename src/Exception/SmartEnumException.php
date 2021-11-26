<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use Exception;
use Throwable;
use function sprintf;

class SmartEnumException extends Exception implements SmartEnumExceptionInterface
{
    final public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string       $value
     * @param class-string $class
     *
     * @return static
     */
    public static function invalidValue(string $value, string $class): self
    {
        return new static(sprintf('The value "%s" is not valid for enum of class "%s"', $value, $class));
    }

    /**
     * @param string $class
     *
     * @return static
     */
    public static function invalidEnumClass(string $class): self
    {
        return new static(sprintf('Class "%s" is not a valid enum', $class));
    }
}
