<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();

return $config->setFinder($finder)
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PHP74Migration' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => false,
        'escape_implicit_backslashes' => false,
        'general_phpdoc_annotation_remove' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'increment_style' => ['style' => 'post'],
        'is_null' => true,
        'list_syntax' => true,
        'logical_operators' => true,
        'modernize_types_casting' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'native_constant_invocation' => true,
        'native_function_invocation' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_unused_imports' => true,
        'no_useless_sprintf' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_private',
                'constant_protected',
                'constant_public',
                'property_private',
                'property_protected',
                'property_public',
                'construct',
                'destruct',
                'magic',
            ],
            'sort_algorithm' => 'none',
        ],
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        'ordered_traits' => true,
        'phpdoc_add_missing_param_annotation' => false,
        'phpdoc_line_span' => [
            'property' => 'multi',
            'method' => 'multi',
        ],
        'phpdoc_to_comment' => false,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_internal_class' => false,
        'php_unit_mock_short_will_return' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_test_class_requires_covers' => false,
        'self_accessor' => true,
        'single_line_throw' => false,
        'string_length_to_empty' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ]);
