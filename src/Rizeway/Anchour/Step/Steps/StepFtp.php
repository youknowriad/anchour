<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\Step;

use jubianchi\Ftp\Ftp;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Output\Symfony\ConsoleOutputAdapter;

class StepFtp extends Step
{
    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if(false === $this->getAdapter()->extension_loaded('ftp'))
        {
            throw new \RuntimeException('FTP extension is not loaded');
        }

        parent::__construct($options, $connections, $options_resolver, $connections_resolver, $adapter);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'local_dir' => '',
            'remote_dir' => ''
        ));
    }

    protected function setDefaultConnections(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'connection'
        ));
    }

    public function run(OutputInterface $output)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $connection = $this->connections['connection'];
        $connection->connect($output);
        $connection->setOutput(new ConsoleOutputAdapter($output));
        $connection->uploadDirectory(getcwd() . '/' . $this->options['local_dir'], $this->options['remote_dir']);

        error_reporting($level);
    }    
}