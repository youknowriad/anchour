<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use jubianchi\Adapter\AdaptableInterface;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Adapter\Adapter;

abstract class Step implements StepInterface, AdaptableInterface
{
    protected $options;

    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    public function __construct(OptionsResolverInterface $resolver, array $options = array(), AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return \jubianchi\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        if(true === is_null($this->adapter)) {
            $this->adapter = new Adapter();
        }

        return $this->adapter;
    }

    /**
     * Define The Step options 
     * @param OptionsResolverInterface $resolver 
     */
    abstract protected function setDefaultOptions(OptionsResolverInterface $resolver);
}