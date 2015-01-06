<?php
namespace Rizeway\Anchour\Config\Resolvers;

use jubianchi\Adapter\AdapterInterface;
use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class EnvironmentResolver extends Resolver
{
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);
    }

    /**
     * Get Required Parameters From Prompt
     *
     * @param ConfigurableInterface $command
     *
     * @return array
     */
    public function getValues(ConfigurableInterface $command)
    {
        $values = array();
        foreach ($this->getVariablesToAskInArray($command->getConfig()) as $key => $var) {
            if (false !== getenv($key)) {
                $values[$key] = getenv($key);
            }
        }

        return $values;
    }
}
