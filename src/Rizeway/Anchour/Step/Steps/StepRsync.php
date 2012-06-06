<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepRsync extends Step
{
    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $output = $status = null;
        exec('which rsync', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('rsync command is not available');
        }

        parent::__construct($options, $connections, $options_resolver, $connections_resolver, $adapter);
    }


    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'key_file'
        ));

        $resolver->setDefaults(array(
            'source_dir' => null,
            'destination_dir' => null,
            'cli_args' => '-avz --progress'
        ));
    }

    protected function setDefaultConnections(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'source' => null,
            'destination' => null
        ));
    }

    public function run(OutputInterface $output)
    {
        if(isset($this->connections['source'])) {
            $source = sprintf(
                '%s@%s:%s',
                $this->connections['source']->getUsername(),
                $this->connections['source']->getHost(),
                $this->options['source_dir']
            );

            $destination = $this->options['destination_dir'];
        } else {
            $source = $this->options['source_dir'];

            $destination = sprintf(
                '%s@%s:%s',
                $this->connections['destination']->getUsername(),
                $this->connections['destination']->getHost(),
                $this->options['destination_dir']
            );
        }

        $status = 0;
        $this->getAdapter()->exec(
          sprintf(
            'rsync %s -e "ssh -i %s" %s %s 2>&1',
            $this->options['cli_args'],
            $this->options['key_file'],
            $source,
            $destination
          ),
          $output,
          $status
        );

        if (0 !== $status)
        {
          throw new \RuntimeException(implode(PHP_EOL, $output));
        }
    }
}