<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums;

use function array_map;

/**
 * @template T of EnumInterface
 */
class EnumFormatter
{
    /**
     * @var EnumFactory<T>
     */
    private EnumFactory $factory;

    /**
     * @param class-string<T> $enumClass
     */
    public function __construct(string $enumClass)
    {
        $this->factory = new EnumFactory($enumClass);
    }

    /**
     * @return non-empty-array<non-empty-string, non-empty-string>
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
     * @return non-empty-array<non-empty-string, non-empty-string>
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
     * @return non-empty-list<non-empty-string>
     */
    public function toValues(): array
    {
        $list = $this->factory->all();

        return array_map(static fn (EnumInterface $enum): string => $enum->getValue(), $list);
    }

    /**
     * @return non-empty-list<non-empty-string>
     */
    public function toDescriptions(): array
    {
        $list = $this->factory->all();

        return array_map(static fn (EnumInterface $enum): string => $enum->getDescription(), $list);
    }
}
