<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\EnumListConverter\EnumListConverterInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumExceptionInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use function gettype;
use function is_array;
use function is_string;

/**
 * @internal
 */
abstract class AbstractEnumGenericArrayType extends Type
{
    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     *
     * @return list<EnumInterface>|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailed(gettype($value), $this->getName());
        }

        if ($value === '') {
            return [];
        }

        $enumListConverter = $this->getEnumListConverter();

        try {
            return $enumListConverter->convertFromStringToEnumList($this->getEnumClass(), $value);
        } catch (SmartEnumExceptionInterface $exception) {
            throw ConversionException::conversionFailedUnserialization(
                $this->getName(),
                'Invalid array value'
            );
        }
    }

    /**
     * @param list<EnumInterface>|mixed $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_array($value)) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                'string',
                ['null', EnumInterface::class . '[]']
            );
        }

        $enums = [];
        foreach ($value as $enum) {
            if (!$enum instanceof EnumInterface) {
                throw ConversionException::conversionFailedInvalidType(
                    $value,
                    'string',
                    ['null', EnumInterface::class . '[]']
                );
            }

            $enums[] = $enum;
        }

        $enumListConverter = $this->getEnumListConverter();

        try {
            return $enumListConverter->convertFromEnumListToString($enums);
        } catch (SmartEnumExceptionInterface $exception) {
            throw ConversionException::conversionFailedUnserialization(
                $this->getName(),
                'Invalid array value'
            );
        }
    }

    abstract protected function getEnumListConverter(): EnumListConverterInterface;

    /**
     * @return class-string<EnumInterface>
     */
    abstract protected function getEnumClass(): string;
}
