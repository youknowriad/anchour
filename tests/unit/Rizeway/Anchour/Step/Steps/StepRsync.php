<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepRsync extends test
{
    public function test__construct()
    {
        $this                        
            ->object(
                new \Rizeway\Anchour\Step\Steps\StepRsync(
                    new \mock\Symfony\Component\OptionsResolver\OptionsResolver(), 
                    array(
                        'key_file' => uniqid(), 
                        'source_connection' => uniqid(), 
                        'destination_connection' => uniqid(),
                        'source_dir' => uniqid(),
                        'destination_dir' => uniqid(),
                        'cli_args' => uniqid(),
                    )
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepRsync(new \mock\Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required option "key_file" is  missing.')
        ;
    }

    public function testRun()
    {        
        $this
            ->if($connections = new \mock\Rizeway\Anchour\Connection\ConnectionHolder())
            ->and($resolver = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and(
                $connection = new \mock\Rizeway\Anchour\Connection\Connections\ConnectionSsh(
                    $resolver,
                    array(
                        'host' => ($host = uniqid()),
                        'username' => ($username = uniqid()),
                        'password' => ($password = uniqid())                        
                    )
                )
            )
            ->and($connections->getMockController()->offsetGet = function() use(&$connection) { return $connection; })
            ->and($adapter->exec = function() {})
            ->and($file = uniqid())                        
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())            
            ->and($message = uniqid())
            ->and($resolver = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and(
                $object = new \Rizeway\Anchour\Step\Steps\StepRsync(
                    $resolver, 
                    array(
                        'key_file' => ($key = uniqid()), 
                        'source_dir' => ($source = uniqid()), 
                        'destination_connection' => uniqid(), 
                        'destination_dir' => ($dest = uniqid())
                    )
                )
            )
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($output, $connections))->isNull()                
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s" %s %s@%s:%s 2>&1', $key, $source, $username, $host, $dest))->once()


            ->if($resolver = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and(
                $object = new \Rizeway\Anchour\Step\Steps\StepRsync(
                    $resolver, 
                    array(
                        'key_file' => ($key = uniqid()), 
                        'source_dir' => ($source = uniqid()), 
                        'source_connection' => uniqid(), 
                        'destination_dir' => ($dest = uniqid())
                    )
                )
            )
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($output, $connections))->isNull()                
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s" %s@%s:%s %s 2>&1', $key, $username, $host, $source, $dest))->once()                    

        ;   
    }
}