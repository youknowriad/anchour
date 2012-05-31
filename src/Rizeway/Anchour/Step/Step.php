<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class Step implements StepInterface
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
}