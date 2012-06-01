<?php

namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;


class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Anchour');

        $this->setCatchExceptions(true);        

        try {
            // Initializing the commands
            $initilizer = new Initializer();
            $initilizer->initialize($this);
        } catch(\Exception $exc) {
            $this->renderException($exc, new ConsoleOutput());
        }     
    }
}