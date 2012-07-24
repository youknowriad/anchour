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
     * @var \Rizeway\Anchour\Config\Resolvers\Resolver[]
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

    /**
     * @param \Rizeway\Anchour\Config\Resolver $resolver
     *
     * @return \Rizeway\Anchour\Config\Resolvers\CompositeResolver
     */
    public function addResolver(Resolver $resolver) {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * @param array $resolvers
     *
     * @return \Rizeway\Anchour\Config\Resolvers\CompositeResolver
     */
    public function addResolvers(array $resolvers) {
        $this->resolvers = array_merge($resolvers, $this->resolvers);

        return $this;
    }

    /**
     * @return \Rizeway\Anchour\Config\Resolvers\Resolver[]
     */
    public function getResolvers() {
        return $this->resolvers;
    }

    /**
     * @param \Rizeway\Anchour\Config\ConfigurableInterface $command
     *
     * @return array
     */
    public function getValues(ConfigurableInterface $command)
    {
        $values = array();
        foreach($this->getResolvers() as $resolver) {
            $values = array_merge($values, $resolver->getValues($command));
        }

        return $values;
    }

    /**
     * @param \Rizeway\Anchour\Config\ConfigurableInterface $command
     *
     * @return array|\mixed[]
     */
    public function resolve(ConfigurableInterface $command)
    {
        $values = array();
        foreach($this->getResolvers() as $resolver) {
            $values = array_merge($values, $resolver->resolve($command));
        }

        return $values;
    }
}
