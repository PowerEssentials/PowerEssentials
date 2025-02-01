<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__) // Scan seluruh file di direktori proyek
    ->exclude('vendor') // Abaikan folder vendor jika menggunakan Composer
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'header_comment' => [
            'header' => <<<EOF
                  ____                        _____                    _   _       _     
                 |  _ \ _____      _____ _ __| ____|___ ___  ___ _ __ | |_(_) __ _| |___ 
                 | |_) / _ \ \ /\ / / _ \ '__|  _| / __/ __|/ _ \ '_ \| __| |/ _` | / __|
                 |  __/ (_) \ V  V /  __/ |  | |___\__ \__ \  __/ | | | |_| | (_| | \__ \\
                 |_|   \___/ \_/\_/ \___|_|  |_____|___/___/\___|_| |_|\__|_|\__,_|_|___/
                                                                                         

                This file is part of PowerEssentials plugins.

                (c) Angga7Togk <kiplihode123321@gmail.com>

                This source code is licensed under the MIT license found in the
                LICENSE file in the root directory of this source tree.
                EOF,
            'location' => 'after_open',
            'comment_type' => 'comment',
            'separate' => 'both',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        'braces' => ['position_after_functions_and_oop_constructs' => 'same'],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'single'],
        'function_typehint_space' => true,
        'lowercase_cast' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
                'use_trait',
                'return',
            ],
        ],
        'no_leading_import_slash' => true,
        'no_trailing_whitespace' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_blank_line_at_eof' => true,
        'single_quote' => true,
        'ternary_operator_spaces' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder);
