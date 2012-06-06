<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Config\Loader;
use Rizeway\Anchour\Step\StepRunner;

class TargetCommand extends Command
{
    /**
     * @var \Rizeway\Anchour\Config\Loader
     */
    protected $loader;

    public function setLoader($loader)
    {
        $this->loader = $loader;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loader->resolveRequiredVariablesForCommand($this->getName(), $output);
        $runner = new StepRunner($this->loader->getCommandSteps($this->getName()));
        $runner->run($output);
    }
}
