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
    public function initialize(Application $console)
    {
        // Checking the anchour config file
        $anchour_config_file = getcwd().'/.anchour';
        if (!file_exists($anchour_config_file))
        {
            throw new \Exception('The .anchour config files was not found in the current directory');
        }
        
        $loader = new Loader($console, $anchour_config_file);

        foreach ($loader->getCommands() as $command_name => $description)
        {
            $console
                ->register($command_name)
                ->setDescription($description)
                ->setCode(function (InputInterface $input, OutputInterface $output) use($command_name, $loader) {
                    $loader->resolveRequiredParameters($command_name, $output);
                    $runner = new StepRunner($loader->getCommandSteps($command_name), $loader->getCommandConnections($command_name));
                    $runner->run($output);
                });

            $console
                ->register('init')
                ->setDescription('Create a default .anchour file')
                ->addOption('force', 'f', InputOption::VALUE_NONE)
                ->setCode(function(InputInterface $input, OutputInterface $output) {
                    $template = <<<YAML
#Here you can define your targets
target:
    connections:
        #Here you can define your connections

    steps:
        #Here you can define your steps
        -
            type: "echo"
            options:
                message: "This is a default <info>echo</info> step"
YAML;

                    if (file_exists('.anchour') && !$input->getOption('force'))
                    {
                      throw new \RuntimeException('File .anchour already exists. To replace it, use the --force/-f option');
                    }

                    $file = new \SplFileObject('.anchour', 'w+');
                    $file->fwrite($template);
                });
        }
    }
}