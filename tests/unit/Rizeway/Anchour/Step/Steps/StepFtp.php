<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepFtp extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepFtp(array(
                        'local_dir' => uniqid(),
                        'remote_dir' => uniqid()
                    ),
                    array('connection' => uniqid()),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver()
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepFtp(array(), array(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "connection" is  missing.')
        ;
    }
}