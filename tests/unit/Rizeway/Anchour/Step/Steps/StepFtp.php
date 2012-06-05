<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepFtp extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepFtp(
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    array(
                        'connection' => uniqid(), 
                        'local_dir' => uniqid(),
                        'remote_dir' => uniqid()
                    )
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepFtp(new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "connection" is  missing.')
        ;
    }
}