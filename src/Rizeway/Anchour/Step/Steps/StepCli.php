<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

class StepCli extends Step
{
    protected function setDefaultOptions()
    {
        $this->addOption('commands', Definition::TYPE_REQUIRED);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getOption('commands') as $command) {
            $this->getAdapter()->passthru($command);
        }
    }
}
