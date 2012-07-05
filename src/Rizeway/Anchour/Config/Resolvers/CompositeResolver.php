<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use jubianchi\Adapter\AdapterInterface;

use Rizeway\Anchour\Console\Application;
use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolvers;
use Rizeway\Anchour\Config\Resolver;

class CompositeResolver extends Resolver
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var \Rizeway\Anchour\Config\Resolver\Resolver[]
     */
    private $resolvers;

    /**
     * @param \Rizeway\Anchour\Console\Application              $application
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \jubianchi\Adapter\AdapterInterface|null          $adapter
     */
    public function __construct(Application $application, InputInterface $input, OutputInterface $output, $adapter = null)
    {
        $this->setAdapter($adapter);

        $this->input = $input;
        $this->output = $output;

        if (($config = $input->getOption('config')) !== null) {
            $this->addResolver(new Resolvers\ConfigurationFileResolver(new \SplFileInfo($config)));
        }

        if (0 < count($input->getArgument('var'))) {
            $this->addResolver(new Resolvers\ArgumentCliResolver($input));
        }

        if ($input->isInteractive()) {
            $this->addResolver(new Resolvers\InteractiveCliResolver($output, $application->getHelperSet()->get('dialog')));
        }
    }

    public function addResolver(Resolver $resolver) {
        $this->resolvers[] = $resolver;

        return $this;
    }

    public function addResolvers(array $resolver) {
        $this->resolvers = array_merge($resolver, $this->resolvers);

        return $this;
    }

    public function getResolvers() {
        return $this->resolvers;
    }

    public function getValues(ConfigurableInterface $command)
    {
        $values = array();
        foreach($this->getResolvers() as $resolver) {
            $values = array_merge($values, $resolver->getValues($command, array_keys($values)));
        }

        return $values;
    }
}
