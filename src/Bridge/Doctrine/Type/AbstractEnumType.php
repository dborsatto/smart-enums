<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Doctrine\Type;

use DBorsatto\SmartEnums\EnumFactory;
use DBorsatto\SmartEnums\EnumInterface;
use DBorsatto\SmartEnums\Exception\SmartEnumException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use function is_string;

abstract class AbstractEnumType extends Type
{
    private const VARCHAR_LENGTH_DEFAULT = 50;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!isset($column['length'])) {
            $column['length'] = self::VARCHAR_LENGTH_DEFAULT;
        }

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     *
     * @return EnumInterface|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?EnumInterface
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }

        try {
            $factory = new EnumFactory($this->getEnumClass());

            return $factory->fromValue($value);
        } catch (SmartEnumException $exception) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    /**
     * @param EnumInterface|mixed $value
     * @param AbstractPlatform    $platform
     *
     * @throws ConversionException
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof EnumInterface) {
            return $value->getValue();
        }

        throw ConversionException::conversionFailedInvalidType($value, 'string', ['null', EnumInterface::class]);
    }

    /**
     * @return string
     * @psalm-return class-string<EnumInterface>
     */
    abstract protected function getEnumClass(): string;
}
