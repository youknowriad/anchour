<?php
namespace Rizeway\Anchour\Step;

use Rizeway\Anchour\Step\Definition\Definition;
use Rizeway\Anchour\Step\Definition\DefinitionInterface;

use jubianchi\Adapter\AdapterInterface;
use jubianchi\Adapter\Adapter;

abstract class Step implements StepInterface
{
    /**
     * The step options
     * @var mixed[]
     */
    protected $options;
    
    /**
     * The connections used by the step
     * @var \Rizeway\Anchour\Connection\ConnectionInterface[]
     */
    private $connections;

    /**
     * @var \Rizeway\Anchour\Step\Definition\Definition
     */
    private $definition;

    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    final public function __construct(array $options = array(), array $connections = array(), AdapterInterface $adapter = null, DefinitionInterface $definition = null)
    {
        $this->setAdapter($adapter);
        $this->setDefinition($definition);

        $this->initialize();

        $this->setDefaultOptions();
        $this->options = $this->getDefinition()->bindOptions($options);

        $this->setDefaultConnections();
        $this->connections = $this->getDefinition()->bindConnections($connections);
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

    public function addOption($name, $type, $default = null) 
    {
        $this->getDefinition()->addOption($name, $type, $default);
    }

    public function hasOption($name) 
    {
        return isset($this->options[$name]);
    }

    public function getOption($name) {
        if(false === $this->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('Option %s is not defined', $name));
        }

        return $this->options[$name];
    }

    public function addConnection($name, $type, $default = null) 
    {
        $this->getDefinition()->addConnection($name, $type, $default);
    }

    public function hasConnection($name) 
    {
        return isset($this->connections[$name]);
    }

    public function getConnection($name) {
        if(false === $this->hasConnection($name)) {
            throw new \InvalidArgumentException(sprintf('Connection %s is not defined', $name));
        }

        return $this->connections[$name];
    }

    /**
     * @return \Rizeway\Anchour\Step\Definition\DefinitionInterface
     */
    private function getDefinition()
    {
        if(null === $this->definition) {
            $this->definition = new Definition();
        }

        return $this->definition;
    }

    public function setDefinition(DefinitionInterface $definition = null)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Define The Step options 
     */
    abstract protected function setDefaultOptions();

    /**
     * Overload to define the connections used by the step
     */
    protected function setDefaultConnections() {}

    protected function initialize() {}
}