<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\StepApplicationAware;
use Rizeway\Anchour\Step\Definition\Definition;

class StepCommand extends StepApplicationAware
{
    protected function setDefaultOptions()
    {
        $this->addOption('command', Definition::TYPE_REQUIRED);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->get($this->getOption('command'));
        $command->run($input, $output);
    }
}
