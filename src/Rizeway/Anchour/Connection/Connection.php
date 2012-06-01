<?php

namespace Rizeway\Anchour\Connection;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class Connection implements ConnectionInterface
{
    protected $options;

    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
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
}