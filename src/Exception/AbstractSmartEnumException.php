<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Exception;

use Exception;
use Throwable;

abstract class AbstractSmartEnumException extends Exception implements SmartEnumExceptionInterface
{
    final public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
