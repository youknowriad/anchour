<?php
namespace tests\unit\Rizeway\Anchour\Config\Resolvers;

use mageekguy\atoum\test;

class EnvironmentResolver extends test {

    public function testGetValues() {
        $this
            ->if($configurable = new \mock\Rizeway\Anchour\Config\ConfigurableInterface())
            ->and($configurable->getMockController()->getConfig = array('%a% abc', 'avx %b%'))
            ->and($object = new \Rizeway\Anchour\Config\Resolvers\EnvironmentResolver())
            ->then()
                ->array($object->getValues($configurable))->isEmpty()
            ->if(putenv("a=foo"))
            ->and(putenv("c=bar"))
            ->then()
                ->array($object->getValues($configurable))->isEqualTo(array('a' => 'foo'))
            ->if(putenv("a"))
            ->and(putenv("b=bar"))
            ->then()
                ->array($object->getValues($configurable))->isEqualTo(array('b' => 'bar'))
            ->if(putenv("a=foo"))
            ->and(putenv("b=bar"))
            ->then()
                ->array($object->getValues($configurable))->isEqualTo(array('a' => 'foo', 'b' => 'bar'))
        ;
    }

    public function testResolve() {
        $this
            ->and($object = new \mock\Rizeway\Anchour\Config\Resolvers\EnvironmentResolver())
            ->and($configurable = new \mock\Rizeway\Anchour\Config\ConfigurableInterface())
            ->and($configurable->getMockController()->getConfig = array())
            ->then()
                ->array($object->resolve($configurable))->isEqualTo(array())
                ->mock($object)
                    ->call('replaceValuesInRecursiveArray')->withArguments(array(), array())->once()
        ;
    }
}
