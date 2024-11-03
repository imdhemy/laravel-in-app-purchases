<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor']);

$ruleSet = [
    '@Symfony' => true,
    // > PHPUnit
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'php_unit_test_annotation' => ['style' => 'annotation'],
    // > Strict
    'declare_strict_types' => true,
    // > Operator
    'not_operator_with_successor_space' => true,
    // > Cast Notation
    'cast_spaces' => ['space' => 'none'],
    // > Import
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],
];

$config = new PhpCsFixer\Config();

$config->setFinder($finder)->setRules($ruleSet)->setRiskyAllowed(true);

return $config;
