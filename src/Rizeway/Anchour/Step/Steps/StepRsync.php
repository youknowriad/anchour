<?php
namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

use Symfony\Component\Console\Output\OutputInterface;

class StepRsync extends Step
{
    public function initialize()
    {
        $output = $status = null;
        $this->getAdapter()->exec('which rsync', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('rsync command is not available');
        }
    }

    protected function setDefaultOptions()
    {
        $this->addOption('key_file', Definition::TYPE_REQUIRED);

        $this->addOption('source_dir', Definition::TYPE_OPTIONAL);
        $this->addOption('destination_dir', Definition::TYPE_OPTIONAL);
        $this->addOption('cli_args', Definition::TYPE_OPTIONAL, '-avz --progress');
    }

    protected function setDefaultConnections()
    {
        $this->addConnection('source', Definition::TYPE_OPTIONAL);
        $this->addConnection('destination', Definition::TYPE_OPTIONAL);
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