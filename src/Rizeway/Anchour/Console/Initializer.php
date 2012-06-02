<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

use Rizeway\Anchour\Step\StepFactory;
use Rizeway\Anchour\Step\StepRunner; 
use Rizeway\Anchour\Connection\ConnectionFactory;
use Rizeway\Anchour\Connection\ConnectionHolder;

class Initializer 
{
    public function initialize(Application $console)
    {
        // Checking the anchour config file
        $anchour_config_file = getcwd().'/.anchour';
        if (!file_exists($anchour_config_file))
        {
            throw new \Exception('The .anchour config files was not found in the current directory');
        }
        
        // Parsing the anchour config file
        $config = Yaml::parse($anchour_config_file);

        foreach ($config as $name => $command_config)
        {
            $description = isset($command_config['description']) ? $command_config['description'] : $name;
            $steps = isset($command_config['steps']) ? $command_config['steps'] : array();
            $connections = isset($command_config['connections']) ? $command_config['connections'] : array();

            $console
                ->register($name)
                ->setDescription($description)
                ->setCode(function (InputInterface $input, OutputInterface $output) use($steps, $connections) {
                    
                    $factory = new StepFactory();
                    $step_objects = array();
                    foreach ($steps as $step) {
                        $step_objects[] = $factory->build($step);
                    }

                    $factory = new ConnectionFactory();
                    $connection_objects = new ConnectionHolder();
                    foreach ($connections as $name => $connection) {
                        $connection_objects[$name] = $factory->build($connection);
                    }

                    if (count($step_objects)) {
                        $runner = new StepRunner($step_objects, $connection_objects);
                        $runner->run($output);
                    }
                });
        }
    }
}