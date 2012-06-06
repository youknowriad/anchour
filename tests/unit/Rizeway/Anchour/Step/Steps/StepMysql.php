<?php
namespace tests\unit\Rizeway\Anchour\Step\Steps;

use mageekguy\atoum\test;

class StepMysql extends test
{
    public function test__construct()
    {
        $this                        
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
                    new \Symfony\Component\OptionsResolver\OptionsResolver(), 
                    new \Symfony\Component\OptionsResolver\OptionsResolver()
                )
            )
            ->isInstanceOf('\\Rizeway\\Anchour\\Step\\Step')            
            ->exception(function() {
                new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array(), 
                    new \Symfony\Component\OptionsResolver\OptionsResolver(),
                    new \Symfony\Component\OptionsResolver\OptionsResolver());
            })
            ->isInstanceOf('\\Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException')
            ->hasMessage('The required options "destination", "source" are missing.')
        ;
    }

    public function testRun()
    {
        $this
            ->if($resolver = new \Symfony\Component\OptionsResolver\OptionsResolver())
            ->and($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and(
                $connection = new \Rizeway\Anchour\Connection\Connections\ConnectionMysql(
                    $resolver,
                    array(
                        'host' => ($host = uniqid()),
                        'username' => ($username = uniqid()),
                        'password' => ($password = uniqid()),
                        'database' => ($database = uniqid())
                    )
                )
            )
            ->and($adapter->passthru = function() {})
            ->and($file = uniqid())    
            ->and($adapter->tempnam = function() use($file) { return $file; })                
            ->and($output = new \mock\Symfony\Component\Console\Output\OutputInterface())            
            ->and($message = uniqid())
            ->and($resolver_options = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and($resolver = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and($object = new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array('source' => $connection, 'destination' => $connection), $resolver_options, $resolver))
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($output))->isNull()                
                ->adapter($adapter)
                ->call('passthru')
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s -e "DROP DATABASE \`%s\`"', $host, $username, $password, $database))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"', $host, $username, $password, $database))->once()
                    ->withArguments(sprintf('mysqldump -h%s -u%s -p%s %s > %s', $host, $username, $password, $database, $file))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -p%s %s < %s', $host, $username, $password, $database, $file))->once()


            ->if($resolver = new \Symfony\Component\OptionsResolver\OptionsResolver())
            ->and(
                $connection = new \Rizeway\Anchour\Connection\Connections\ConnectionMysql(
                    $resolver,
                    array(
                        'host' => ($host = uniqid()),
                        'username' => ($username = uniqid()),                        
                        'database' => ($database = uniqid())
                    )
                )
            )   
            ->and($resolver_options = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())
            ->and($resolver_connections = new \mock\Symfony\Component\OptionsResolver\OptionsResolver())         
            ->and($object = new \Rizeway\Anchour\Step\Steps\StepMysql(array(), array('source' => $connection, 'destination' => $connection), 
                $resolver_options, $resolver_connections))
            ->and($object->setAdapter($adapter))
            ->then()
                ->variable($object->run($output))->isNull()                
                ->adapter($adapter)
                ->call('passthru')
                    ->withArguments(sprintf('mysql -h%s -u%s -e "DROP DATABASE \`%s\`"', $host, $username, $database))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"', $host, $username, $database))->once()
                    ->withArguments(sprintf('mysqldump -h%s -u%s %s > %s', $host, $username, $database, $file))->once()
                    ->withArguments(sprintf('mysql -h%s -u%s %s < %s', $host, $username, $database, $file))->once()
        ;   
    }
}