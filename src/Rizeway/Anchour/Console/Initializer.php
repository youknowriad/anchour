<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
                    var_dump($steps);
                });
        }
    }
}