<?php

namespace Rizeway\Anchour\Connection;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnectionFactory
{
    /**
     * Build a step from a conf array
     *
     * @param  mixed[]                   $config The Conf array
     * @return Rizeway\Anchour\Step\Step
     */
    public function build($config)
    {
        if (!isset($config['type'])) {
            throw new \Exception('The connection type is required');
        }

        $class = 'Rizeway\Anchour\Connection\Connections\Connection'.ucfirst($config['type']);
        if (!class_exists($class)) {
            throw new \Exception(sprintf('The connection %s was not found', $config['type']));
        }

        $options = isset($config['options']) ? $config['options'] : array();

        return new $class(new OptionsResolver(), $options);
    }
}
