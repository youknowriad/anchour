<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepCliPhar extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepCliPhar(
                    array(
                        'directory' => uniqid(),
                        'name'  => uniqid(),
                        'stub'  => uniqid(),
                        'output' => '.',
                        'regexp' => null,
                        'chmod' => true
                    ),
                    array()
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepCliPhar(array(),array());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required options "directory", "name", "stub" are missing.')
        ;
    }
}