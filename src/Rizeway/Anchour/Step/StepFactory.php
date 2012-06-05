<?php

namespace Rizeway\Anchour\Step;

use jubianchi\Adapter\Adaptable;

class StepFactory extends Adaptable
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
            throw new \RuntimeException('The step type is required');
        }

        $class = 'Rizeway\Anchour\Step\Steps\Step'.ucfirst($config['type']);
        if (!$this->getAdapter()->class_exists($class))
        {
            throw new \RuntimeException(sprintf('The step %s was not found', $config['type']));
        }

        $options = isset($config['options']) ? $config['options'] : array();

        return $this->getInstance($class, $options);
    }

    public function getInstance($class, $options) 
    {
        return new $class(new \Symfony\Component\OptionsResolver\OptionsResolver(), $options);
    }
}