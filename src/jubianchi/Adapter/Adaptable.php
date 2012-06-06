<?php
namespace jubianchi\Adapter;

use jubianchi\Adapter\AdaptableInterface;

class Adaptable implements AdaptableInterface {
    /**
     * @var \jubianchi\Adapter\AdapterInterface
     */
    private $adapter;

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
}