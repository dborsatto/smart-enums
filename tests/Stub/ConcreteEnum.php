<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Stub;

use DBorsatto\SmartEnums\AbstractEnum;

final class ConcreteEnum extends AbstractEnum
{
    private const VALUES = [
        'value1' => 'description1',
        'value2' => 'description2',
    ];

    protected static function getValues(): array
    {
        return self::VALUES;
    }

    public static function value1(): self
    {
        return self::newInstance('value1');
    }

    public static function value2(): self
    {
        return self::newInstance('value2');
    }
}
