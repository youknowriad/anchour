<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

use Rizeway\Anchour\Config\Loader;
use Rizeway\Anchour\Console\Command\InitCommand;

class Application extends BaseApplication
{
    protected $initializer;

    public function __construct(Initializer $initializer)
    {
        parent::__construct('Anchour');

        $this->setCatchExceptions(true);

        $this->initializer = $initializer;
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        try
        {
            $this->initialize($this->initializer);
        }
        catch(\Exception $exc)
        {
            if('init' !== $input->getFirstArgument())
            {
                $this->renderException($exc, $output);
            }
        }

        $this->add(new InitCommand());

        return parent::doRun($input, $output);
    }



    protected function initialize()
    {
        // Checking the anchour config file
        $anchour_config_file = getcwd().'/.anchour';
        if (!file_exists($anchour_config_file))
        {
            throw new \Exception('The .anchour config files was not found in the current directory');
        }

        $loader = new Loader($this, $anchour_config_file);

        // Initializing the commands
        $this->initializer->initialize($this, $loader);

        return $this;
    }
}