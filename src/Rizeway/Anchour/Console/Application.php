<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Anchour');

        // Checking the anchour config file
        $anchour_config_file = getcwd().'/.anchour';
        if (!file_exists($anchour_config_file))
        {
            throw new \Exception('The .anchour config files was not found in the current directory');
        }

        // Parsing the anchour config file
        $config = Yaml::parse($anchour_config_file);

        // Initializing the commands
        $initilizer = new Initializer();
        $initilizer->initialize($this, $config);
    }
}