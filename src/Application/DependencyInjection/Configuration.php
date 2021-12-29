<?php

namespace App\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * DOC
 * NEED to be named <ns>\DependencyInjection\Configuration.php
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('can_be_anything');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('listener')
                    ->canBeEnabled() // will be TRUE by default
                    ->children()
                        ->scalarNode('id')->defaultValue('main')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
