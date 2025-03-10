<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php84: true)
    ->withTypeCoverageLevel(3)
    ->withDeadCodeLevel(3)
    ->withCodeQualityLevel(3)
    ->withRules([
	AddReturnTypeDeclarationFromYieldsRector::class,
    ]);

