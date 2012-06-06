<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OOSSH\SSH2\Connection;
use OOSSH\SSH2\Authentication\Password;

class StepSsh extends Step
{
    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $output = $status = null;
        exec('which ssh', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('ssh command is not available');
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
        $connection = $this->connections['connection'];
        $connection->connect($output);

        foreach ($this->options['commands'] as $command)
        {
            $connection->exec($command, function($stdio, $stderr) use($output) {
              $output->write($stdio);

              if('' !== $stderr)
              {
                throw new \RuntimeException($stderr);
              }
            });
        }
    }
}