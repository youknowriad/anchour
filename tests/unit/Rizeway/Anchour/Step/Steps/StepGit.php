<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepGit extends test
{
    public function test__construct()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->then()
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

            ->and($adapter->extension_loaded = false)
            ->then()
                ->exception(function() use($adapter) {
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
                        new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                        $adapter
                    );
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('SSH2 extension is not loaded')
        ;
    }
}