<?php
namespace Rizeway\Anchour\Config;

class Validator {
    public function validate(array $config = array()) 
    {
        $builder = new \Symfony\Component\Config\Definition\Builder\TreeBuilder();
        $root = $builder->root('anchour');

        $root
            ->children()
                ->arrayNode('connections')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->end()
                            ->arrayNode('options')
                                ->prototype('variable')->end() 
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('description')->end()
                            ->arrayNode('steps')
                                ->prototype('array')
                                    ->children()                                    
                                        ->scalarNode('type')->end()
                                        ->arrayNode('options')  
                                            ->prototype('variable')->end()                                           
                                        ->end()
                                        ->arrayNode('connections')
                                            ->prototype('scalar')->end()                                           
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
        $processor->process($builder->buildTree(), (array)$config);
    }
}