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
    public function initialize(Application $console, Loader $loader)
    {
        foreach ($loader->getCommands() as $command_name => $description)
        {
            $console->add($this->getInstance($command_name, $description, $loader));
        }
    }

    public function getInstance($name, $description, Loader $loader = null)
    {
        $command = new \Rizeway\Anchour\Console\Command\TargetCommand($name);
        $command->setDescription($description);

        if (false === is_null($loader))
        {
            $command->setLoader($loader);
        }

        return $command;
    }
}