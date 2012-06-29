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
            ->and($loader = new \mock\Rizeway\Anchour\Config\Loader(uniqid()))
            ->and($loader->getMockController()->getCommands = array(
                'foo' => ($fooCommand = new \mock\Symfony\Component\Console\Command\Command('foo')),
                'bar' => ($barCommand = new \mock\Symfony\Component\Console\Command\Command('bar'))
            ))
            ->then()
                ->variable($object->initialize($application, $loader))->isNull()
                ->mock($loader)
                    ->call('getCommands')->once()
                ->mock($application)
                    ->call('add')->withArguments($fooCommand)->once()
                    ->call('add')->withArguments($barCommand)->once()
        ;
    }
}