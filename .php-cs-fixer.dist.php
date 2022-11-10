<?php

declare(strict_types=1);

$includedPatterns = [
    __DIR__.'/src',
    __DIR__.'/tests',
];

$finder = (new PhpCsFixer\Finder())
    ->in($includedPatterns)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true);

return $config
    ->setRules([
        '@Symfony' => true,
        // > PHPUnit
        'php_unit_method_casing' => ['case' => 'snake_case'],
        // > Strict
        'declare_strict_types' => true,
        // > Operator
        'not_operator_with_successor_space' => true,
        // > Cast Notation
        'cast_spaces' => ['space' => 'none'],
    ])->setFinder($finder);
