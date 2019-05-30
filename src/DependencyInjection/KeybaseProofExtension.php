<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class KeybaseProofExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.php');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('keybase.username', null);
        if (isset($config['my_username'])) {
            $container->setParameter('keybase.username', $config['my_username']);
            unset($config['my_username']);
        }
        $container->setParameter('keybase_proof.config', $config);
    }
}
