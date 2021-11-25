<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Tests\Stub;

use DBorsatto\SmartEnums\Bridge\Doctrine\Type\AbstractEnumJsonListType;

class DoctrineEnumJsonListType extends AbstractEnumJsonListType
{
    private string $enumClass = '';

    public static function createForEnum(string $enumClass): self
    {
        $type = new self();
        $type->enumClass = $enumClass;

        return $type;
    }

    protected function getEnumClass(): string
    {
        return $this->enumClass;
    }

    public function getName(): string
    {
        return 'enum_list';
    }
}
