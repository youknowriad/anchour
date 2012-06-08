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

use jubianchi\Adapter\AdaptableInterface;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Adapter\Adapter;

class Application extends BaseApplication implements AdaptableInterface
{
    protected $initializer;

    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    public function __construct(Initializer $initializer, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        parent::__construct('Anchour', ANCHOUR_VERSION);

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

    /**
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter = null)
    {
      $this->adapter = $adapter;

      return $this;
    }

    /**
     * @return \jubianchi\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
      if(true === is_null($this->adapter)) {
        $this->adapter = new Adapter();
      }

      return $this->adapter;
    }

    protected function initialize()
    {
        // Checking the anchour config file
        $anchour_config_file = getcwd().'/.anchour';
        if (false === $this->getAdapter()->file_exists($anchour_config_file))
        {
            throw new \Exception('The .anchour config files was not found in the current directory');
        }

        $loader = new Loader($this, $anchour_config_file);

        // Initializing the commands
        $this->initializer->initialize($this, $loader);

        return $this;
    }
}