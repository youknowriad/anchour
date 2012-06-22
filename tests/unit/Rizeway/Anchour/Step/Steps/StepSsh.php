<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepSsh extends test
{
    public function test__construct()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->then()
                ->object(
                    new \Rizeway\Anchour\Step\Steps\StepSsh(
                        array(
                            'commands' => array()
                        ),
                        array(
                            'connection' => uniqid()
                        ),
                        $adapter
                    )
                )
                ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')
                ->exception(function() use($adapter) {
                    new \Rizeway\Anchour\Step\Steps\StepSsh(array(), array(), $adapter);
                })
                ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
                ->hasMessage('The required option "commands" is  missing.')
        ;
    }
}