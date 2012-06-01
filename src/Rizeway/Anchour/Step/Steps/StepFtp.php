<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Connection\ConnectionHolder;
use Rizeway\Anchour\Step\Step;

use jubianchi\Ftp\Ftp;

class StepFtp extends Step
{
    public function __construct(array $options = array())
    {
        if(false === extension_loaded('ftp'))
        {
            throw new \RuntimeException('FTP extension is not loaded');
        }

        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'connection'
        ));

        $resolver->setDefaults(array(
            'local_dir' => '',
            'remote_dir' => ''
        ));
    }

    public function run(OutputInterface $output, ConnectionHolder $connections)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $connection = $connections[$this->options['connection']];
        $connection->connect($output);
        $connection->setOutput($output);
        $connection->uploadDirectory(getcwd() . '/' . $this->options['local_dir'], $this->options['remote_dir']);

        error_reporting($level);
    }    
}