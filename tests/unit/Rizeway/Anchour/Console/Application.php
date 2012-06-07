<?php
namespace tests\unit\Rizeway\Anchour\Console;

use mageekguy\atoum\test;

class Application extends test
{
    public function testDoRun()
    {
        $this
            ->if($initializer = new \mock\Rizeway\Anchour\Console\Initializer())
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->file_exists = true)
            ->and($input = new \mock\Symfony\Component\Console\Input\InputInterface())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($object = new \mock\Rizeway\Anchour\Console\Application($initializer, $adapter))
            ->then()
                ->integer($object->doRun($input, $output))->isEqualTo(0)

            ->if($adapter->file_exists = false)
            ->and($object->getMockController()->renderException = function() {})
            ->then()
                ->integer($object->doRun($input, $output))->isEqualTo(0)
                ->mock($object)
                    ->call('renderException')->once()
        ;
    }

    public function testGetAdapter()
    {
        $this
            ->if($initializer = new \mock\Rizeway\Anchour\Console\Initializer())
            ->and($object = new \mock\Rizeway\Anchour\Console\Application($initializer))
            ->then()
                ->object($object->getAdapter())->isInstanceOf('\\jubianchi\\Adapter\\AdapterInterface')

            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($object->setAdapter($adapter))
            ->then()
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testSetAdapter()
    {
        $this
            ->if($initializer = new \mock\Rizeway\Anchour\Console\Initializer())
            ->and($object = new \mock\Rizeway\Anchour\Console\Application($initializer))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->setAdapter(null))->isIdenticalTo($object)
                ->object($object->setAdapter(new \jubianchi\Adapter\Test\Adapter()))->isIdenticalTo($object)
        ;
    }
}