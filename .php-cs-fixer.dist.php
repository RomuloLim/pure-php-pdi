<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->exclude(['vendor'])
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'single_space',
                '=' => 'align_single_space_minimal',
            ],
        ],
        'no_unused_imports' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'modernize_types_casting' => true,
    ])
    ->setFinder($finder);
