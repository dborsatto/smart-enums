<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\EnumListConverter;

use function explode;
use function implode;

class SymbolSeparatedValuesEnumListConverter extends AbstractEnumListConverter
{
    /**
     * @var non-empty-string
     */
    private string $symbol;

    /**
     * @param non-empty-string $symbol
     */
    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    protected function convertToArray(string $value): array
    {
        if ($value === '') {
            return [];
        }

        /** @psalm-suppress LessSpecificReturnStatement */
        return explode($this->symbol, $value);
    }

    protected function convertToString(array $values): string
    {
        return implode($this->symbol, $values);
    }
}
