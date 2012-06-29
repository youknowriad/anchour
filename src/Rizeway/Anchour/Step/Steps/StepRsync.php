<?php
namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

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

    public function run(InputInterface $input, OutputInterface $output)
    {
        if(true === $this->hasConnection('source')) {
            $source = sprintf(
                '%s@%s:%s',
                $this->getConnection('source')->getUsername(),
                $this->getConnection('source')->getHost(),
                $this->getOption('source_dir')
            );

            $destination = $this->getOption('destination_dir');
        } else {
            $source = $this->getOption('source_dir');

            $destination = sprintf(
                '%s@%s:%s',
                $this->getConnection('destination')->getUsername(),
                $this->getConnection('destination')->getHost(),
                $this->getOption('destination_dir')
            );
        }

        $status = 0;
        $this->getAdapter()->exec(
          sprintf(
            'rsync %s -e "ssh -i %s" %s %s 2>&1',
            $this->getOption('cli_args'),
            $this->getOption('key_file'),
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