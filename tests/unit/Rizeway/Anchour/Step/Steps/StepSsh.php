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
                    array(
                        'commands' => array()                        
                    ),
                    array(
                        'connection' => uniqid()                     
                    ),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver()
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepSsh(array(), array(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "commands" is  missing.')
        ;
    }
}