<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\Definition;

$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false)
    ->setBindings([
        'array $keybaseProofConfig' => '%keybase_proof.config%',
        'string $keybaseUsername' => '%keybase.username%',
    ])
;

$this->registerClasses($definition, '💻3615\\Keybase\\ProofBundle\\', '../../*', './');
