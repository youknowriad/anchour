<?php
namespace tests\unit\Rizeway\Anchour\Step;

use mageekguy\atoum\test;

class StepRunner extends test
{
    public function testRun()
    {
        $this 
            ->if($steps = array(
                $step = new \mock\Rizeway\Step\Step(),
                $otherStep = new \mock\Rizeway\Step\Step()
            ))
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($object = new \Rizeway\Anchour\Step\StepRunner($steps))
            ->then()
                ->variable($object->run($output))->isNull()
                ->mock($step)->call('run')->withArguments($output)->once()
                ->mock($otherStep)->call('run')->withArguments($output)->once()
        ;
    }
}