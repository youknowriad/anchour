<?php
namespace tests\unit\Rizeway\Anchour\Config\Resolvers;

use mageekguy\atoum\test;

class InteractiveCliResolver extends test {
    public function testResolve() {
        $this
            ->if($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($dialog = new \mock\Symfony\Component\Console\Helper\DialogHelper())
            ->and($object = new \mock\Rizeway\Anchour\Config\Resolvers\InteractiveCliResolver($output, $dialog))
            ->and($configurable = new \mock\Rizeway\Anchour\Config\ConfigurableInterface())
            ->and($configurable->getMockController()->getConfig = $config = array(
                'key' => '%foo%',
                'otherKey' => 'Another %bar%'
            ))
            ->and($object->getMockController()->getVariablesToAskInArray = array(
                'foo' => '%foo%',
                'bar' => '%bar%'
            ))
            ->and($dialog->getMockController()->ask = $value = uniqid())
            ->then()
                ->array($object->resolve($configurable))->isEqualTo(array(
                    'foo' => $value,
                    'bar' => $value
                ))
                ->mock($dialog)
                    ->call('ask')
                        ->withArguments($output, 'Entrer the <info>foo (%foo%)</info> : ')->once()
                        ->withArguments($output, 'Entrer the <info>bar (%bar%)</info> : ')->once()
                ->mock($object)
                    ->call('replaceValuesInRecursiveArray')->withArguments(
                        $config, 
                        array(
                            'foo' => $value,
                            'bar' => $value
                        )
                    )->once()
        ;
    }
}