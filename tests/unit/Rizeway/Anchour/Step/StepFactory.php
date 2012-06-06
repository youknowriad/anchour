<?php
namespace tests\unit\Rizeway\Anchour\Step;

use mageekguy\atoum\test;

class StepFactory extends test
{
    public function testBuild()
    {
        $this 
            ->if($object = new \mock\Rizeway\Anchour\Step\StepFactory()) 
            ->and($step = new \StdClass())
            ->and($object->getMockController()->getInstance = $step)           
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->class_exists = true)
            ->and($object->setAdapter($adapter))
            ->then()
                ->object($object->build(array('type' => uniqid()), array()))->isIdenticalTo($step)

            ->if($object = new \Rizeway\Anchour\Step\StepFactory())
            ->then()
                ->exception(function() use($object) {
                    $object->build(array(), array());
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('The step type is required')

            ->if($object = new \Rizeway\Anchour\Step\StepFactory())
            ->and($adapter->class_exists = false)
            ->and($object->setAdapter($adapter))
            ->and($type = uniqid())
            ->then()
                ->exception(function() use($object, $type) {
                    $object->build(array('type' => $type), array());
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage(sprintf('The step %s was not found', $type))
        ;
    }
}