<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use function array_map;

class EnumFormatter
{
    private EnumFactory $factory;

    /**
     * @param class-string<EnumInterface> $enumClass
     *
     * @throws SmartEnumExceptionInterface
     */
    public function __construct(string $enumClass)
    {
        $this->factory = new EnumFactory($enumClass);
    }

    /**
     * @return non-empty-array<string, string>
     */
    public function toValueDescriptionList(): array
    {
        $list = $this->factory->all();

        $data = [];
        foreach ($list as $enum) {
            $data[$enum->getValue()] = $enum->getDescription();
        }

        return $data;
    }

    /**
     * @deprecated
     *
     * @return non-empty-array<string, string>
     */
    public function toKeyValueList(): array
    {
        return $this->toValueDescriptionList();
    }

    /**
     * @return non-empty-array<string, string>
     */
    public function toDescriptionValueList(): array
    {
        $list = $this->factory->all();

        $data = [];
        foreach ($list as $enum) {
            $data[$enum->getDescription()] = $enum->getValue();
        }

        return $data;
    }

    /**
     * @deprecated
     *
     * @return non-empty-array<string, string>
     */
    public function toValueKeyList(): array
    {
        return $this->toDescriptionValueList();
    }

    /**
     * @return non-empty-list<string>
     */
    public function toValues(): array
    {
        $list = $this->factory->all();

        return array_map(static fn (EnumInterface $enum): string => $enum->getValue(), $list);
    }

    /**
     * @return non-empty-list<string>
     */
    public function toDescriptions(): array
    {
        $list = $this->factory->all();

        return array_map(static fn (EnumInterface $enum): string => $enum->getDescription(), $list);
    }
}
