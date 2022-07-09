<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use function explode;
use function implode;

class SymbolSeparatedValuesEnumListConverter extends AbstractEnumListConverter
{
    private string $symbol;

    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    protected function convertToArray(string $value): array
    {
        if ($value === '') {
            return [];
        }

        return explode($this->symbol, $value);
    }

    protected function convertToString(array $values): string
    {
        return implode($this->symbol, $values);
    }
}
