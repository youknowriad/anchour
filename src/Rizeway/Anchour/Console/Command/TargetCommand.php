<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\StepRunner;
use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\ResolverInterface;
use Rizeway\Anchour\Config\Resolvers;

class TargetCommand extends Command implements ConfigurableInterface
{
    /**
     * @var array
     */
    private $steps = array();

    /**
     * @param array $config
     *
     * @return \Rizeway\Anchour\Console\Command\Command
     */
    public function setSteps(array $steps)
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * @return array
     */
    public function getSteps()
    {
        return $this->steps;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->resolver = new Resolvers\InteractiveCliResolver($output, $this->getApplication()->getHelperSet()->get('dialog'));
        if (($config = $input->getOption('config')) !== null) {
            $this->resolver = new Resolvers\ConfigurationFileResolver($config);
        }

        $this->resolveConfiguration($this->resolver);

        $runner = new StepRunner($this->getSteps());
        $runner->run($input, $output);
    }

    public function resolveConfiguration(ResolverInterface $resolver)
    {
        foreach ($this->getSteps() as $step) {
            $step->resolveConfiguration($resolver);
        }
    }
}
