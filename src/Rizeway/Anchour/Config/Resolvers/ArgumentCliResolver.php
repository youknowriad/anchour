<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Console\Input\InputInterface;

use jubianchi\Adapter\AdapterInterface;

use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class ArgumentCliResolver extends Resolver
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     */
    public function __construct(InputInterface $input, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        $this->input = $input;
    }

    /**
     * Get Required Parameters From Prompt
     *
     * @param \Rizeway\Anchour\Config\ConfigurableInterface $command
     *
     * @return array
     */
    public function getValues(ConfigurableInterface $command)
    {
        $values = array();
        foreach($this->input->getArgument('var') as $var) {
            $var = explode('=', $var);
            $values[$var[0]] = $var[1];
        }

        return $values;
    }
}
