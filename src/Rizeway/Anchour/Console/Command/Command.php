<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Config\Loader;

class Command extends BaseCommand
{
    /**
     * @var \Rizeway\Anchour\Config\Loader
     */ 
    private $loader;

    /**
     * @return \Rizeway\Anchour\Config\Loader
     */ 
    public function getLoader() 
    {
        return $this->loader;
    }

    /**
     * @param \Rizeway\Anchour\Config\Loader $loader
     *
     * @return \Rizeway\Anchour\Console\Command\Command 
     */ 
    public function setLoader(Loader $loader) 
    {
        $this->loader = $loader;

        return $this;
    }
}
