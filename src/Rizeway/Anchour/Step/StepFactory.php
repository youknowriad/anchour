<?php

namespace Rizeway\Anchour\Step;

use jubianchi\Adapter\Adaptable;

class StepFactory extends Adaptable
{
    /**
     * Build a step from a conf array
     * 
     * @param  mixed[] $config The Conf array
     * @param Rizeway\Anchour\Connection\ConnectionInterface[]
     * @return Rizeway\Anchour\Step\Step
     */
    public function build($config, $connections)
    {
        if (!isset($config['type']))
        {
            throw new \RuntimeException('The step type is required');
        }

        $class = 'Rizeway\Anchour\Step\Steps\Step'.ucfirst($config['type']);
        if (!$this->getAdapter()->class_exists($class))
        {
            throw new \RuntimeException(sprintf('The step %s was not found', $config['type']));
        }

        $options = isset($config['options']) ? $config['options'] : array();
        $connection_names = isset($config['connections']) ? $config['connections'] : array();
        $connection_objects = array();
        foreach ($connection_names as $key => $connection_name) {
            $connection_objects[$key] = $connections[$connection_name];
        }

        return $this->getInstance($class, $options, $connection_objects);
    }

    public function getInstance($class, $options, $connections) 
    {
        return new $class($options, $connections);
    }
}