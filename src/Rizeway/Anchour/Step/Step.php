<?php
namespace Rizeway\Anchour\Step;

use Rizeway\Anchour\Step\Definition\Definition;

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
     * @var \Rizeway\Anchour\Step\Definition\Definition
     */
    private $definition;

    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

    public function __construct(array $options = array(), array $connections = array(), AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

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

    public function addConnection($name, $type, $default = null) 
    {
        $this->getDefinition()->addConnection($name, $type, $default);
    }

    private function getDefinition()
    {
        if(null === $this->definition) {
            $this->definition = new Definition();
        }

        return $this->definition;
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