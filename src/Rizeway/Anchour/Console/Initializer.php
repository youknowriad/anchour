<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\StepFactory;
use Rizeway\Anchour\Step\StepRunner; 

class Initializer 
{
    public function initialize(Application $console, $anchour_config)
    {
        foreach ($anchour_config as $name => $command_config)
        {
            $description = isset($command_config['description']) ? $command_config['description'] : $name;
            $steps = isset($command_config['steps']) ? $command_config['steps'] : array();

            $console
                ->register($name)
                ->setDescription($description)
                ->setCode(function (InputInterface $input, OutputInterface $output) use($steps) {
                    
                    $factory = new StepFactory();
                    $step_objects = array();
                    foreach ($steps as $step) {
                        $step_objects[] = $factory->build($step);
                    }

                    if (count($step_objects)) {
                        $runner = new StepRunner($step_objects);
                        $runner->run();
                    }
                });
        }
    }
}