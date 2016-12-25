<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 20:37
 */

namespace CoderGeek\Bundle\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('codergeek_maintenance');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('maintenance')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')
                            ->defaultValue("CoderGeekMaintenanceBundle:Maintenance:index.html.twig")
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('whitelist')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('ips')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('route')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('driver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('\CoderGeek\Bundle\MaintenanceBundle\Drivers\FileDriver')
                        ->end()
                        ->variableNode('options')
                            ->defaultValue(['filePath' => "%kernel.root_dir%/lock"])
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('response')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('code')
                            ->defaultValue(302)
                        ->end()
                        ->scalarNode('status')
                            ->defaultValue("Webshop Under Maintenance")
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
