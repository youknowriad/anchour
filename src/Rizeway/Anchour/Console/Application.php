<?php
namespace Rizeway\Anchour\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Config\Loader;
use Rizeway\Anchour\Console\Command\InitCommand;
use Rizeway\Anchour\Exception\AnchourNotFoundException;

use jubianchi\Adapter\AdaptableInterface;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Adapter\Adapter;

class Application extends BaseApplication implements AdaptableInterface
{
    /**
     * @var \Rizeway\Anchour\Console\Initializer
     */
    protected $initializer;
    protected $resolved_values = array();

    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    public function __construct(Initializer $initializer, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        parent::__construct('Anchour', ANCHOUR_VERSION);

        $this->getDefinition()->addOption(new InputOption('config', 'c', InputOption::VALUE_REQUIRED, 'Configuration file'));
        $this->setCatchExceptions(true);

        $this->initializer = $initializer;
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->initialize($input);

            return parent::doRun($input, $output);
        } catch (\Exception $exc) {
            $this->renderException($exc, $output);

            if ($exc instanceof AnchourNotFoundException) {
                parent::doRun($input, $output);
            }

            return $exc->getCode() ?: 255;
        }
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
      if (true === is_null($this->adapter)) {
        $this->adapter = new Adapter();
      }

      return $this->adapter;
    }

    protected function initialize(InputInterface $input)
    {
        $this->add(new InitCommand());

        if('init' !== $input->getFirstArgument()) {
            $anchour_config_file = getcwd().'/.anchour';

            if (false === $this->getAdapter()->file_exists($anchour_config_file)) {
                throw new AnchourNotFoundException('The .anchour config files was not found in the current directory');
            }

            $this->initializer->initialize($this, new Loader($anchour_config_file));
        }

        return $this;
    }

    public function setResolvedValues($values)
    {
        $this->resolved_values = $values;
    }

    public function getResolvedValues()
    {
        return $this->resolved_values;
    }
}
