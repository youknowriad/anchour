<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OOSSH\SSH2\Connection;
use OOSSH\SSH2\Authentication\Password;

use jubianchi\Adapter\AdapterInterface;

class StepSsh extends Step
{
    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if(false === $this->getAdapter()->extension_loaded('ssh2'))
        {
            throw new \RuntimeException('SSH2 extension is not loaded');
        }

        parent::__construct($options, $connections, $options_resolver, $connections_resolver, $adapter);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'commands'
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
        $this->getConnection()->connect($output);

        foreach ($this->options['commands'] as $command)
        {
            $this->getConnection()->exec($command, function($stdio, $stderr) use($output) {
              $output->write($stdio);

              if('' !== $stderr)
              {
                throw new \RuntimeException($stderr);
              }
            });
        }
    }

    /**
     * @return string
     */
    protected function getConnection() {
        return  $this->connections['connection'];
    }
}