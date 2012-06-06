<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use jubianchi\Adapter\AdaptableInterface;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Adapter\Adapter;

abstract class Step implements StepInterface, AdaptableInterface
{

    /**
     * The step options
     * @var mixed[]
     */
    protected $options;
    
    /**
     * The connections used by the step
     * @var string[]
     */
    protected $connections;


    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        // Resolve Options
        $options_resolver = $options_resolver ? $options_resolver : new OptionsResolver();
        $this->setDefaultOptions($options_resolver);
        $this->options = $options_resolver->resolve($options);

        // Resolve Connections
        $connections_resolver = $connections_resolver ? $connections_resolver : new OptionsResolver();
        $this->setDefaultConnections($connections_resolver);
        $this->connections = $connections_resolver->resolve($connections);
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

    /**
     * Overload to define the connections used by the step
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultConnections(OptionsResolverInterface $resolver) {}
}