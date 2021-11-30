<?php

$header = <<<EOF
This file is part of the GoogleTranslateCommandBundle.

(c) Maxime Pinot <contact@maximepinot.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('tests/Fixtures/App/var/cache')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'braces' => [
            'position_after_control_structures' => 'next'
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['package', 'subpackage'],
        ],
        'header_comment' => ['header' => $header, 'location' => 'after_open'],
        'mb_str_functions' => true,
        'multiline_comment_opening_closing' => true,
        'no_alternative_syntax' => true,
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'return_assignment' => true,
        'simplified_null_return' => true,
        'strict_comparison' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
