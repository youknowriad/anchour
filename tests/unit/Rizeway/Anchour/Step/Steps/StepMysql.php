<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepMysql extends test
{
    public function test__construct()
    {
        $this      
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->setExecStatus(0))               
            ->then()
                ->object(
                    new \Rizeway\Anchour\Step\Steps\StepMysql(
                        array(
                            'create_database' => true,
                            'drop_database' => true                        
                        ),
                        array(
                            'source' => uniqid(), 
                            'destination' => uniqid()                      
                        ),
                        $adapter
                    )
                )
                ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            
                ->exception(function() use($adapter) {
                    new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array(), $adapter);
                })
                ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
                ->hasMessage('The required options "destination", "source" are missing.')
        ;
    }

    public function testRun()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->setExecStatus(0)) 
            ->and($adapter->passthru = function() {})
            ->and($file = uniqid())  
            ->and($adapter->tempnam = function() use($file) { return $file; })  
            ->and(
                $connection = new \Rizeway\Anchour\Connection\Connections\ConnectionMysql(
                    new \Symfony\Component\OptionsResolver\OptionsResolver(),
                    array(
                        'host' => ($host = uniqid()),
                        'username' => ($username = uniqid()),
                        'password' => ($password = uniqid()),
                        'database' => ($database = uniqid())
                    )
                )
            )                            
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())            
            ->and($input = new \mock\Symfony\Component\Console\Input\InputInterface())
            ->and($message = uniqid())
            ->and($object = new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array('source' => $connection, 'destination' => $connection), $adapter))
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('passthru')
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s -e "DROP DATABASE \`%s\`"', $host, $username, $password, $database))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"', $host, $username, $password, $database))->once()
                    ->withArguments(sprintf('mysqldump -h%s -u%s -p%s %s > %s', $host, $username, $password, $database, $file))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s %s < %s', $host, $username, $password, $database, $file))->once()
            

            ->if(
                $connection = new \Rizeway\Anchour\Connection\Connections\ConnectionMysql(
                    new \Symfony\Component\OptionsResolver\OptionsResolver(),
                    array(
                        'host' => ($host = uniqid()),
                        'username' => ($username = uniqid()),                        
                        'database' => ($database = uniqid())
                    )
                )
            )        
            ->and($object = new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array('source' => $connection, 'destination' => $connection), $adapter))
            ->then()
                ->variable($object->run($input, $output))->isNull()
                ->adapter($adapter)
                ->call('passthru')
                    ->withArguments(sprintf('mysql -h%s -u%s -e "DROP DATABASE \`%s\`"', $host, $username, $database))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"', $host, $username, $database))->once()
                    ->withArguments(sprintf('mysqldump -h%s -u%s %s > %s', $host, $username, $database, $file))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s %s < %s', $host, $username, $database, $file))->once()
        ;   
    }
}