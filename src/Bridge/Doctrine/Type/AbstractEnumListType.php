<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use function is_array;
use function is_string;
use function restore_error_handler;
use function serialize;
use function set_error_handler;
use function unserialize;

abstract class AbstractEnumListType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

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
     * @return list<EnumInterface>
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        if (null === $value || '' === $value) {
            return [];
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedUnserialization(
                $this->getName(),
                'Invalid unserialized value'
            );
        }

        /** @psalm-suppress UnusedClosureParam */
        set_error_handler(function (int $code, string $message): bool {
            throw ConversionException::conversionFailedUnserialization($this->getName(), $message);
        });

        try {
            $factory = new EnumFactory($this->getEnumClass());

            $unserializedList = unserialize($value);
            if (!is_array($unserializedList)) {
                throw ConversionException::conversionFailedUnserialization(
                    $this->getName(),
                    'Invalid unserialized value'
                );
            }

            $enums = [];
            foreach ($unserializedList as $unserializedValue) {
                if (!is_string($unserializedValue)) {
                    throw ConversionException::conversionFailedUnserialization(
                        $this->getName(),
                        'Invalid unserialized value'
                    );
                }

                $enums[] = $factory->fromValue($unserializedValue);
            }

            return $enums;
        } catch (SmartEnumException $exception) {
            throw ConversionException::conversionFailedInvalidType($value, static::getName(), ['string']);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @param list<EnumInterface>|mixed $value
     * @param AbstractPlatform          $platform
     *
     * @throws ConversionException
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (null === $value) {
            $value = [];
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

        return serialize($enumValues);
    }

    /**
     * @return string
     * @psalm-return class-string<EnumInterface>
     */
    abstract protected function getEnumClass(): string;
}
