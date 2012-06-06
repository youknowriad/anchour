<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepGit extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepGit(
                    array(
                        'repository' => uniqid(), 
                        'remote_dir' => uniqid(),
                        'clean_scm' => true,
                        'remove_existing' => false,
                        'commands' => array()
                    ),
                    array('connection' => uniqid()),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver()
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepGit(array(), array(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required options "remote_dir", "repository" are missing.')
        ;
    }
}