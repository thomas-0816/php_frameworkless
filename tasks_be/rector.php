<?php

use Rector\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector;
use Rector\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector;
use Rector\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(php85: true)
    ->withPhpVersion(PhpVersion::PHP_85)
    ->withAttributesSets(phpunit: true)
    ->withRootFiles()
    ->withSkip([
        SafeDeclareStrictTypesRector::class,
        AddNameToNullArgumentRector::class,
        AddNameToBooleanArgumentRector::class,
        ExplicitAttributeNamedArgsRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        InlineIfToExplicitIfRector::class,
        ReadOnlyPropertyRector::class,
        RenameVariableToMatchMethodCallReturnTypeRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        __DIR__ . '/src/vendor',
        __DIR__ . '/tests/vendor',
        __DIR__ . '/tests/data',
    ])
    ->withPreparedSets(true, true, true, true, true, true, true, true, true);
