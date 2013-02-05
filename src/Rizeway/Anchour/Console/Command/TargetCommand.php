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

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this
            ->addArgument('var', \Symfony\Component\Console\Input\InputArgument::IS_ARRAY)
        ;
    }

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
     * @return \Rizeway\Anchour\Step\Step[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setResolver(new Resolvers\CompositeResolver($this->getApplication(), $input, $output));        
        $this->getResolver()->setResolvedValues($this->getApplication()->getResolvedValues());
        $values = $this->resolveConfiguration($this->getResolver());
        $this->getApplication()->setResolvedValues($values);

        $runner = new StepRunner($this->getApplication(), $this->getSteps());
        return $runner->run($input, $output);
    }

    public function resolveConfiguration(ResolverInterface $resolver)
    {
        $values = array();
        foreach ($this->getSteps() as $step) {
            $values += $step->resolveConfiguration($resolver);
        }

        return $values;
    }
}
