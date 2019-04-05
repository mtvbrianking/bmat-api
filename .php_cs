<?php

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_trailing_comma_in_singleline_array' => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'blank_line_before_statement' => [
        'statements' => ['return']
    ],
    'no_short_echo_tag' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'no_useless_else' => true,
    'ordered_imports' => [
        'sortAlgorithm' => 'length',
    ],
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_indent' => true,
    'phpdoc_no_package' => true,
    'phpdoc_order' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_trim' => true,
    'phpdoc_var_without_name' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_summary' => true,
    'single_quote' => true,
    'single_line_comment_style' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline_array' => true,
    'trim_array_spaces' => true,
];

$excludes = [
    'bootstrap/cache',
    'node_modules',
    'public',
    'resources',
    'storage',
    'vendor',

];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude($excludes)
            ->in(__DIR__.'/app')
            ->in(__DIR__.'/config')
            ->in(__DIR__.'/database')
            ->in(__DIR__.'/routes')
            ->in(__DIR__.'/tests')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );
