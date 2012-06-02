<?php

namespace Rizeway\Anchour\Connection;

use Symfony\Component\Console\Output\OutputInterface;

class ConnectionHolder implements \ArrayAccess
{

    /**
     * The connections
     * @var Connection[]
     */
    private $connections;

    /**
     * @param Connection[] $connections
     */
    public function __construct(array $connections = array())
    {
        $this->connections = $connections;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->connections);
    }

    public function offsetGet($offset)
    {
        return $this->connections[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->connections[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->connections[$offset]);
    }


}