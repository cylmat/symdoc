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
        // enable config
        return $this->getEnableConfiguration();

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

    ## Feature flag behavior
    private function getEnableConfiguration()
    {
        $treeBuilder = new TreeBuilder('enable_config');

        # https://symfony.com/doc/5.0/components/config/definition.html
        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('enableflags')
                ->useAttributeAsKey('enableflag')
                    ->arrayPrototype()
                        ->info('name of flag')
                        ->treatNullLike(['enabled' => false])
                        ->children()
                            ->booleanNode('valid')->end()
                            ->scalarNode('expression')->end()
                            ->arrayNode('options')
                                ->children()
                                    ->scalarNode('rot13')->defaultValue('none')->end()
                                ->end()
                            ->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
