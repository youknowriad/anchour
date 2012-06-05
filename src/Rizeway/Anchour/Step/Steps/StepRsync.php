<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Connection\ConnectionHolder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepRsync extends Step
{
    public function __construct(OptionsResolverInterface $resolver, array $options = array())
    {
        $output = $status = null;
        exec('which rsync', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('rsync command is not available');
        }

        parent::__construct($resolver, $options);
    }


    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'key_file'
        ));

        $resolver->setDefaults(array(
            'source_connection' => null,
            'destination_connection' => null,
            'source_dir' => null,
            'destination_dir' => null,
            'cli_args' => '-avz --progress'
        ));
    }

    public function run(OutputInterface $output, ConnectionHolder $connections)
    {
        if(isset($this->options['source_connection'])) {
            $source = sprintf(
                '%s@%s:%s',
                $connections[$this->options['source_connection']]->getUsername(),
                $connections[$this->options['source_connection']]->getHost(),
                $this->options['source_dir']
            );

            $destination = $this->options['destination_dir'];
        } else {
            $source = $this->options['source_dir'];

            $destination = sprintf(
                '%s@%s:%s',
                $connections[$this->options['destination_connection']]->getUsername(),
                $connections[$this->options['destination_connection']]->getHost(),
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