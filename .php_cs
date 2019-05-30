<?php

$header = <<<OEF
This file is part of the 3615/keybase-proof-bundle project.

(c) Romain Gautier <gh@romain.sh>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
OEF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
 
        'header_comment' => ['header' => $header],
        'array_syntax' => ['syntax' => 'short'],
        'native_function_invocation' => true,
 
        'ordered_class_elements' => true,
        'ordered_imports' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in('src')
            ->in('tests')
    )
;
