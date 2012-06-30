<?php

namespace Rizeway\Anchour\Connection;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use \Rizeway\Anchour\Config\ResolverInterface;
use \Rizeway\Anchour\Config\ConfigurableInterface;

abstract class Connection implements ConnectionInterface, ConfigurableInterface
{
    protected $options;

    public function __construct(OptionsResolverInterface $resolver, array $options = array())
    {
        $this->setDefaultOptions($resolver);
        $this->setConfig($resolver->resolve($options));
    }

    /**
     * Define The Step options
     * @param OptionsResolverInterface $resolver
     */
    abstract protected function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * @abstract
     *
     * @return string
     */
    abstract public function __toString();

    public function resolveConfiguration(ResolverInterface $resolver)
    {
        $this->setConfig($resolver->resolve($this));
    }

    /**
     * @param array $config
     *
     * @return \Rizeway\Anchour\Connection\Connection
     */
    public function setConfig(array $config)
    {
        $this->options = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->options;
    }
}
