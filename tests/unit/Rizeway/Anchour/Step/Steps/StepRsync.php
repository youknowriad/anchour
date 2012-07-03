<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepRsync extends test
{
    public function test__construct()
    {
        $this   
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->setExecStatus(0))               
            ->then()                     
                ->object(
                    new \Rizeway\Anchour\Step\Steps\StepRsync(
                        array(
                            'key_file' => uniqid(),
                            'source_dir' => uniqid(),
                            'destination_dir' => uniqid(),
                            'cli_args' => uniqid(),
                        ),
                        array(
                            'source' => uniqid(), 
                            'destination' => uniqid(),
                        ),
                        $adapter
                    )
                )
                ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            
                ->exception(function() {
                    new \Rizeway\Anchour\Step\Steps\StepRsync(array(), array());
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
            ->and($adapter->setExecStatus(0)) 
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
            ->and($adapter->exec = function() {})
            ->and($file = uniqid())                        
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())
            ->and($input = new \mock\Symfony\Component\Console\Input\InputInterface())
            ->and($message = uniqid())
            ->and(
                $object = new \Rizeway\Anchour\Step\Steps\StepRsync(
                    array(
                        'key_file' => ($key = uniqid()), 
                        'source_dir' => ($source = uniqid()),
                        'destination_dir' => ($dest = uniqid())
                    ),
                    array(
                        'destination' => $connection
                    ),
                    $adapter
                )
            )
            ->and($adapter->function_exists = true)
            ->and($adapter->posix_isatty = true)
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s" %s %s@%s:%s 2>&1', $key, $source, $username, $host, $dest))->once()


            ->if(
                $object = new \Rizeway\Anchour\Step\Steps\StepRsync(
                    array(
                        'key_file' => ($key = uniqid()), 
                        'source_dir' => ($source = uniqid()),
                        'destination_dir' => ($dest = uniqid())
                    ), 
                    array(
                        'source' => $connection
                    )
                )
            )
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s" %s@%s:%s %s 2>&1', $key, $username, $host, $source, $dest))->once()

            ->if($adapter->function_exists = false)
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s -o \"NumberOfPasswordPrompts 0\"" %s@%s:%s %s 2>&1', $key, $username, $host, $source, $dest))->once()

            ->and($adapter->posix_isatty = false)
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('exec')
                    ->withArguments(sprintf('rsync -avz --progress -e "ssh -i %s -o \"NumberOfPasswordPrompts 0\"" %s@%s:%s %s 2>&1', $key, $username, $host, $source, $dest))->exactly(2)
        ;   
    }
}