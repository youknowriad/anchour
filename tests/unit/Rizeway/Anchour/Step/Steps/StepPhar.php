<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepPhar extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepPhar(
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    array(
                        'directory' => uniqid(),
                        'name'  => uniqid(),
                        'stub'  => uniqid(),
                        'index' => null,
                        'output' => '.',
                        'regexp' => null
                    )
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepPhar(new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required options "directory", "name", "stub" are missing.')
        ;
    }
}