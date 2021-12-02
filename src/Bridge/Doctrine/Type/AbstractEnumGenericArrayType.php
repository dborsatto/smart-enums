<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
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
     * @param string|mixed     $value
     * @param AbstractPlatform $platform
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

        if ($value === '') {
            return [];
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailed(gettype($value), $this->getName());
        }

        try {
            $factory = new EnumFactory($this->getEnumClass());

            $values = $this->convertDatabaseStringToEnumValues($value);
            $enums = [];
            foreach ($values as $arrayValue) {
                if (!is_string($arrayValue)) {
                    throw ConversionException::conversionFailedUnserialization(
                        $this->getName(),
                        'Invalid array value'
                    );
                }

                $enums[] = $factory->fromValue($arrayValue);
            }

            return $enums;
        } catch (SmartEnumExceptionInterface $exception) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }
    }

    /**
     * @param list<EnumInterface>|mixed $value
     * @param AbstractPlatform          $platform
     *
     * @throws ConversionException
     *
     * @return string|null
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

        $enumValues = [];
        foreach ($value as $enum) {
            if (!$enum instanceof EnumInterface) {
                throw ConversionException::conversionFailedInvalidType(
                    $value,
                    'string',
                    ['null', EnumInterface::class . '[]']
                );
            }

            $enumValues[] = $enum->getValue();
        }

        return $this->convertEnumValuesToDatabaseString($enumValues);
    }

    /**
     * @param string $value
     *
     * @throws ConversionException
     *
     * @return list<mixed>
     */
    abstract protected function convertDatabaseStringToEnumValues(string $value): array;

    /**
     * @param list<string> $values
     *
     * @throws ConversionException
     *
     * @return string
     */
    abstract protected function convertEnumValuesToDatabaseString(array $values): string;

    /**
     * @return class-string<EnumInterface>
     */
    abstract protected function getEnumClass(): string;
}
