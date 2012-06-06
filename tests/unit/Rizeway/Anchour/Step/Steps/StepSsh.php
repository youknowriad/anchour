<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepSsh extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepSsh(
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    array(
                        'connection' => uniqid(), 
                        'commands' => array()                        
                    )
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepSsh(new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required options "commands", "connection" are missing.')
        ;
    }
}