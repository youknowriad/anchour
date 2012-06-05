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
                ->object($object->build(array('type' => uniqid())))->isIdenticalTo($step)

            ->exception(function() {
                $object = new \Rizeway\Anchour\Step\StepFactory();
                $object->build(array());
            })                         
            ->isInstanceOf('\\RuntimeException')
            ->hasMessage('The step type is required')
        ;
    }
}