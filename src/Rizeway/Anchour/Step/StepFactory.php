<?php

namespace Rizeway\Anchour\Step;

class StepFactory
{
    /**
     * Build a step from a conf array
     * 
     * @param  mixed[] $config The Conf array
     * @return Rizeway\Anchour\Step\Step
     */
    public function build($config)
    {
        if (!isset($config['type']))
        {
            throw new \Exception('The step type is required');
        }

        $class = 'Rizeway\Anchour\Step\Steps\Step'.ucfirst($config['type']);
        if (!class_exists($class))
        {
            throw new \Exception(sprintf('The step %s was not found', $config['type']));
        }

        $options = isset($config['options']) ? $config['options'] : array();

        return new $class($options);
    }
}