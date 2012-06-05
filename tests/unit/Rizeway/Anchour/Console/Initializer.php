<?php
namespace tests\unit\Rizeway\Anchour\Console;

use mageekguy\atoum\test;

class Initializer extends test
{
    public function testRun()
    {
        $this
            ->if($object = new \mock\Rizeway\Anchour\Console\Initializer())
            ->and($application = new \mock\Rizeway\Anchour\Console\Application($object))
            ->and($loader = new \mock\Rizeway\Anchour\Config\Loader($application, ''))
            ->and($loader->getMockController()->getCommands = array(
                'foo' => 'Foo command',
                'bar' => 'Bar command'
            ))
            ->and($fooCommand = new \mock\Symfony\Component\Console\Command\Command('foo'))
            ->and($barCommand = new \mock\Symfony\Component\Console\Command\Command('bar'))
            ->and($object->getMockController()->getInstance = function($name) use($fooCommand, $barCommand) {
                return ${$name . 'Command'};
            })
            ->then()
                ->variable($object->initialize($application, $loader))->isNull()
                ->mock($loader)
                    ->call('getCommands')->once()
                ->mock($application)
                    ->call('add')->withArguments($fooCommand)->once()
                    ->call('add')->withArguments($barCommand)->once()
        ;
    }

    public function testGetInstance()
    {
        $this
            ->if($object = new \mock\Rizeway\Anchour\Console\Initializer())
            ->and($name = uniqid())
            ->and($description = uniqid())
            ->then()
                ->object($command = $object->getInstance($name, $description))->isInstanceOf('\\Rizeway\\Anchour\\Console\\Command\\TargetCommand')
                ->string($command->getName())->isEqualTo($name)
                ->string($command->getDescription())->isEqualTo($description)
        ;
    }
}