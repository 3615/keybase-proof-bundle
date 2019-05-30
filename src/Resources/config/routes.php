<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use 💻3615\Keybase\ProofBundle\Controller\ExposeKeybaseProofConfigController;

return function (RoutingConfigurator $routes) {
    $routes->add('keybase-proof-config', '/.well-known/keybase-proof-config.json')
        ->controller(ExposeKeybaseProofConfigController::class)
    ;
};
