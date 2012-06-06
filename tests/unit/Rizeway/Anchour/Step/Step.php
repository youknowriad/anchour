<?php
namespace tests\unit\Rizeway\Anchour\Step;

use mageekguy\atoum\test;

class Step extends test
{
    public function test__construct()
    {
        $this 
            ->if($resolver = new \mock\Symfony\Component\OptionsResolver\OptionsResolverInterface())
            ->and($object = new \mock\Rizeway\Anchour\Step\Step($resolver))            
            ->then()
                ->object($object)->isInstanceOf('\\Rizeway\\Anchour\\Step\\StepInterface')
                ->mock($resolver)
                    ->call('resolve')                    
                        ->withArguments(array())->once()
        ;
    }

    public function testGetAdapter()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())            
            ->and($object = new \mock\Rizeway\Anchour\Step\Step(new \mock\Symfony\Component\OptionsResolver\OptionsResolverInterface()))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->getAdapter())->isInstanceOf('\\jubianchi\\Adapter\\AdapterInterface')

            ->if($object->setAdapter($adapter))
            ->then()
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testSetAdapter()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())            
            ->and($object = new \mock\Rizeway\Anchour\Step\Step(new \mock\Symfony\Component\OptionsResolver\OptionsResolverInterface()))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->setAdapter(null))->isIdenticalTo($object)
                ->object($object->setAdapter($adapter))->isIdenticalTo($object)
        ;
    }
}