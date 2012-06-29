<?php
namespace Rizeway\Anchour\Config;

class Validator
{
    public function validate(array $config = array())
    {
        $builder = new \Symfony\Component\Config\Definition\Builder\TreeBuilder();
        $root = $builder->root('anchour');

        $root
            ->children()
                ->arrayNode('connections')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->cannotBeEmpty()->end()
                            ->arrayNode('options')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('description')->end()
                            ->arrayNode('steps')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')->cannotBeEmpty()->end()
                                        ->arrayNode('options')
                                            ->prototype('variable')->end()
                                        ->end()
                                        ->arrayNode('connections')
                                            ->prototype('scalar')->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $processor = new \Symfony\Component\Config\Definition\Processor();
        $processor->process($builder->buildTree(), (array) $config);
    }
}
