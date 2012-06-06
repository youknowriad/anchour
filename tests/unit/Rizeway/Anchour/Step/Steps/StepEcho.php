<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepEcho extends test
{
    public function test__construct()
    {
        $this                        
            ->object(new \Rizeway\Anchour\Step\Steps\StepEcho(array('message' => uniqid()), array(),
                new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                new \mock\Symfony\Component\OptionsResolver\OptionsResolver()))
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepEcho(array(), array(), 
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "message" is  missing.')
        ;
    }

    public function testRun()
    {
        $this
            ->if($connections = new \mock\Rizeway\Anchour\Connection\ConnectionHolder())
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())            
            ->and($message = uniqid())
            ->and($object = new \Rizeway\Anchour\Step\Steps\StepEcho(array('message' => $message), array(), 
                new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                new \mock\Symfony\Component\OptionsResolver\OptionsResolver()))
            ->then()
                ->variable($object->run($output, $connections))->isNull()
                ->mock($output)
                    ->call('writeln')                    
                        ->withArguments($message)->once()
        ;   
    }
}