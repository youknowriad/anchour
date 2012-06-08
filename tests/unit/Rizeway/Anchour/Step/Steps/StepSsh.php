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
                    )
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepSsh(array(), array());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "commands" is  missing.')
        ;
    }
}