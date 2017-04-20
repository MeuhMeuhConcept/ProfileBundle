<?php

namespace MMC\Profile\Bundle\ProfileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mmc_profile');

        $rootNode
            ->children()
                ->scalarNode('profile_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('userProfile_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
