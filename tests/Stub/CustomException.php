<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Stub;

use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use Exception;

class CustomException extends Exception implements SmartEnumExceptionInterface
{
}
