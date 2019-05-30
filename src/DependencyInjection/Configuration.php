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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('keybase_proof');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('my_username')
            ->end()

            ->integerNode('version')
                ->example('1 // To bump when updating config')
                ->isRequired()
            ->end()

            ->scalarNode('domain')
                ->example('beeactivists.com')
                ->isRequired()
            ->end()
            ->scalarNode('display_name')
                ->example('Bee Activists')
                ->isRequired()
            ->end()

            ->arrayNode('username')
                ->children()
                    ->scalarNode('re')
                        ->example('^[a-zA-Z0-9_]{2,20}$')
                        ->isRequired()
                    ->end()
                    ->scalarNode('min')
                        ->example('2')
                    ->end()
                    ->scalarNode('max')
                        ->example('20')
                    ->end()
                ->end()
            ->end() // username

            ->scalarNode('brand_color')
                ->example('\'#FFB800\'')
                ->isRequired()
            ->end()

            ->arrayNode('logo')
                ->children()
                    ->scalarNode('svg_black')
                        ->example('https://beeactivists.com/small-black-logo.svg')
                        ->isRequired()
                    ->end()
                    ->scalarNode('svg_full')
                        ->example('https://beeactivists.com/full-color.logo.svg')
                        ->isRequired()
                    ->end()
                ->end()
            ->end() // logo

            ->scalarNode('description')
                ->example('Next gen social network using big data & AI in the cloud 🤖☁️.')
                ->isRequired()
            ->end()
            ->scalarNode('prefill_url')
                ->example('https://beeactivists.com/new-profile-proof?kb_username=%%{kb_username}&username=%%{username}&token=%%{sig_hash}&kb_ua=%%{kb_ua}')
                ->isRequired()
            ->end()
            ->scalarNode('profile_url')
                ->example('https://beeactivists.com/profile/%%{username}')
                ->isRequired()
            ->end()
            ->scalarNode('check_url')
                ->example('https://api.beeactivists.com/keybase-proofs.json?username=%%{username}')
                ->isRequired()
            ->end()

            ->arrayNode('check_path')
                ->example('["signatures"]')
                ->isRequired()
                ->beforeNormalization()
                    ->ifString()
                    ->then(function ($v) { return [$v]; })
                ->end()
                ->requiresAtLeastOneElement()
                ->prototype('scalar')
                ->end()
            ->end() // check_path

            ->arrayNode('avatar_path')
                ->example('["avatar"]')
                ->isRequired()
                ->beforeNormalization()
                    ->ifString()
                    ->then(function ($v) { return [$v]; })
                ->end()
                ->requiresAtLeastOneElement()
                ->prototype('scalar')
                ->end()
            ->end() // avtar_path

            ->arrayNode('contact')
                ->example('["admin@beeactivists.com", "sassybedazzle@keybase"]')
                ->isRequired()
                ->beforeNormalization()
                    ->ifString()
                    ->then(function ($v) { return [$v]; })
                ->end()
                ->requiresAtLeastOneElement()
                ->prototype('scalar')
                ->end()
            ->end() // contact

            ->end()
        ;

        return $treeBuilder;
    }
}
