<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$includedPatterns = [
  __DIR__ . '/src',
  __DIR__ . '/tests',
];

$rules = [
  '@PSR12' => true,
  'array_syntax' => ['syntax' => 'short'],
  'ordered_imports' => ['sort_algorithm' => 'alpha'],
  'no_unused_imports' => true,
  'not_operator_with_successor_space' => true,
  'trailing_comma_in_multiline' => true,
  'phpdoc_scalar' => true,
  'unary_operator_spaces' => true,
  'binary_operator_spaces' => true,
  'blank_line_before_statement' => [
    'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
  ],
  'phpdoc_single_line_var_spacing' => true,
  'phpdoc_var_without_name' => true,
  'method_argument_space' => [
    'keep_multiple_spaces_after_comma' => false,
    'on_multiline' => 'ensure_fully_multiline',
  ],
];

$finder = Finder::create()
  ->in($includedPatterns)
  ->name('*.php')
  ->notName('.*.blade.php')
  ->ignoreDotFiles(true)
  ->ignoreVCS(true);

$config = new Config();
$config->setRules($rules);
$config->setFinder($finder);

return $config;

