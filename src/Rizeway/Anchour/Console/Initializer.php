<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\StepRunner; 
use Rizeway\Anchour\Config\Loader;

class Initializer
{
    public function initialize(Application $application, Loader $loader)
    {
        foreach ($loader->getCommands() as $command)
        {
            $application->add($command);
        }
    }
}