<?php

/**
 * Based on https://github.com/symfony/panther/blob/master/.php_cs.dist
 */

$header = <<<'HEADER'
This file is part of smartbooster/authentication-bundle.
(c) Nicolas Bastien <nicolas.bastien@smartbooster.io>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
HEADER;

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_open',
        ],
        'modernize_types_casting' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized'], 'scope' => 'namespaced'],
        'no_extra_consecutive_blank_lines' => [
            'break',
            'continue',
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'throw',
            'use',
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => true,
        ],
        'phpdoc_order' => true,
        'psr4' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'ternary_to_null_coalescing' => true,
    ])
    ->setFinder($finder)
;