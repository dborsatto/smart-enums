<?php

declare(strict_types=1);

namespace DBorsatto\SmartEnums\Bridge\Symfony\Form\Type;

use DBorsatto\SmartEnums\Bridge\Symfony\Form\Transformer\EnumToStringTransformer;
use DBorsatto\SmartEnums\EnumFormatter;
use DBorsatto\SmartEnums\EnumInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function is_subclass_of;

class EnumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var class-string<EnumInterface> $enumClass */
        $enumClass = $options['enum_class'];

        $builder->addModelTransformer(new EnumToStringTransformer($enumClass));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('enum_class');
        $resolver->setAllowedTypes('enum_class', 'string');
        $resolver->setAllowedValues('enum_class', function (string $value): bool {
            return is_subclass_of($value, EnumInterface::class);
        });

        $resolver->setDefault('choices', function (Options $options): array {
            /** @var class-string<EnumInterface> $enumClass */
            $enumClass = $options['enum_class'];
            $formatter = new EnumFormatter($enumClass);

            return $formatter->toValueKeyList();
        });
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
