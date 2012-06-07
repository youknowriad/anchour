<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepFtp extends test
{
    public function test__construct()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = false)
            ->then()
                ->exception(function() use($adapter) {
                    new \Rizeway\Anchour\Step\Steps\StepFtp(
                        array(),
                        array(),
                        new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                        new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                        $adapter
                    );
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('FTP extension is not loaded')

            ->if($adapter->extension_loaded = true)
            ->then()
                ->exception(function() {
                    new \Rizeway\Anchour\Step\Steps\StepFtp(
                        array(),
                        array(),
                        new \mock\Symfony\Component\OptionsResolver\OptionsResolver(),
                        new \mock\Symfony\Component\OptionsResolver\OptionsResolver()
                    );
                })
                ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
                ->hasMessage('The required option "connection" is  missing.')

                ->object(
                    new \Rizeway\Anchour\Step\Steps\StepFtp(array(
                            'local_dir' => uniqid(),
                            'remote_dir' => uniqid()
                        ),
                        array('connection' => uniqid()),
                        new \Symfony\Component\OptionsResolver\OptionsResolver(),
                        new \Symfony\Component\OptionsResolver\OptionsResolver(),
                        $adapter
                    )
                )
                ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')
        ;
    }
}